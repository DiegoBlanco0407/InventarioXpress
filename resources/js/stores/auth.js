import { defineStore } from 'pinia';
import axios from 'axios';

// Configure axios interceptors for global error handling
// Track if we're already refreshing to prevent loops
let isRefreshing = false;
let refreshSubscribers = [];

// Function to retry failed requests
function subscribeTokenRefresh(callback) {
  refreshSubscribers.push(callback);
}

function onTokenRefreshed(token) {
  refreshSubscribers.forEach(callback => callback(token));
  refreshSubscribers = [];
}

function onRefreshError() {
  refreshSubscribers = [];
}

// Request interceptor - add token to all requests
axios.interceptors.request.use(
  config => {
    // Get token from localStorage on every request if it's not in the headers
    if (!config.headers['Authorization']) {
      const token = localStorage.getItem('invx_token');
      if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
      }
    }
    return config;
  },
  error => Promise.reject(error)
);

// Response interceptor - handle 401 errors
axios.interceptors.response.use(
  response => response,
  async error => {
    const originalRequest = error.config;
    
    // If it's a 401 error and we haven't tried to refresh the token yet
    if (error.response?.status === 401 && !originalRequest._retry) {
      if (isRefreshing) {
        // Wait for token refresh
        return new Promise((resolve) => {
          subscribeTokenRefresh(token => {
            originalRequest.headers['Authorization'] = `Bearer ${token}`;
            resolve(axios(originalRequest));
          });
        });
      }
      
      originalRequest._retry = true;
      isRefreshing = true;
      
      // Try to refresh token or validate existing one
      const token = localStorage.getItem('invx_token');
      
      if (token) {
        try {
          // Try to validate token
          const response = await axios.get('/api/v1/auth/me', {
            headers: { 'Authorization': `Bearer ${token}` }
          });
          
          if (response.status === 200) {
            // Token is still valid
            onTokenRefreshed(token);
            originalRequest.headers['Authorization'] = `Bearer ${token}`;
            isRefreshing = false;
            return axios(originalRequest);
          }
        } catch (refreshError) {
          // Token refresh failed, redirect to login
          console.error('Token refresh failed:', refreshError);
          onRefreshError();
          isRefreshing = false;
          
          // Handle logout - this will be called by the store instance
          // No redirigir si ya estamos en la landing page o páginas de autenticación
          if (!['/login', '/', '/register'].includes(window.location.pathname)) {
            localStorage.removeItem('invx_token');
            window.location.href = '/'; // Redirigir a landing page en lugar de login
          }
        }
      }
    }
    
    return Promise.reject(error);
  }
);

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: typeof window !== 'undefined' ? (localStorage.getItem('invx_token') || null) : null,
    loading: false,
    error: null,
    errors: {},
  }),
  getters: {
    isAuthenticated: (s) => !!s.token,
  },
  actions: {
    _applyToken(token){
      this.token = token;
      if (token) {
        localStorage.setItem('invx_token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      } else {
        localStorage.removeItem('invx_token');
        delete axios.defaults.headers.common['Authorization'];
      }
    },
    async initialize(){
      // Check if token exists
      if (this.token) {
        // Set the token in axios headers
        this._applyToken(this.token);
        
        try { 
          // Try to load user data with current token
          await this.me(); 
          console.log('Auth initialized successfully');
          return true;
        } catch (e) {
          // If this fails, token is likely invalid
          console.warn('Auth token validation failed:', e.message);
          
          // Clear invalid token
          this._applyToken(null);
          
          // Do not redirect to login from initialization
          return false;
        }
      }
      return false;
    },
    async login(email, password) {
      this.loading = true; this.error = null;
      try {
        const { data } = await axios.post('/api/v1/auth/login', { email, password });
        // Sanctum response: data.data contains { token, usuario }
        const payload = data?.data ?? data?.datos ?? data;
        this._applyToken(payload?.token || null);
        this.user = payload?.usuario || null;
        return true;
      } catch (e) {
        this.error = e?.response?.data?.message || e?.response?.data?.mensaje || 'Credenciales inválidas';
        return false;
      } finally { this.loading = false; }
    },
    async register({ nombre, email, password, password_confirmation }){
      this.loading = true; this.error = null; this.errors = {};
      try {
        const { data } = await axios.post('/api/v1/auth/register', { nombre, email, password, password_confirmation });
        // Sanctum response: data.data contains { token, usuario }
        const payload = data?.data ?? data?.datos ?? data;
        // aplicar token y usuario (auto-login)
        this._applyToken(payload?.token || null);
        this.user = payload?.usuario || null;
        return true;
      } catch (e) {
        const resp = e?.response?.data || {};
        const errs = resp?.errors || {};
        this.errors = errs;
        // Mensajes amigables
        if (errs?.email?.length) {
          this.error = 'El email ya está registrado';
        } else if (errs?.password?.length) {
          this.error = 'La contraseña no cumple los requisitos';
        } else if (errs?.nombre?.length) {
          this.error = 'Revisa el nombre ingresado';
        } else {
          this.error = resp?.message || resp?.mensaje || 'No se pudo registrar';
        }
        return false;
      } finally { this.loading = false; }
    },
    async me(){
      try {
        console.log('Retrieving user data with token:', this.token?.substring(0, 10) + '...');
        const { data } = await axios.get('/api/v1/auth/me');
        console.log('Raw auth response:', JSON.stringify(data));
        
        // Extract user data - Sanctum returns data.data with user object
        const payload = data?.data ?? data?.datos ?? data;
        
        // Ensure role is captured correctly, checking all possible field names
        let userObject = payload || null;
        
        if (userObject) {
          console.log('User data received:', JSON.stringify(userObject));
          // Make sure role field is available (handle different API naming conventions)
          if (userObject.id === 3) {
            // Always force role 1 for user ID 3 regardless of what's set
            const originalRol = userObject.rol;
            const originalRole = userObject.role;
            console.log(`User ID 3 detected. Original rol: ${originalRol}, role: ${originalRole}`);
            console.log('Forcing admin role (1) for user ID 3');
            userObject = { ...userObject, rol: 1, role: 1 };
          }
          
          // Normalize role field
          if (userObject.role !== undefined && userObject.rol === undefined) {
            userObject.rol = userObject.role;
          } else if (userObject.rol !== undefined && userObject.role === undefined) {
            userObject.role = userObject.rol;
          }
          
          console.log('Processed user object:', JSON.stringify(userObject));
        }
        
        this.user = userObject;
        return this.user;
      } catch (error) {
        console.error('Error retrieving user data:', error);
        throw error;
      }
    },
    async updateProfile({ nombre, email }){
      this.loading = true; this.error = null;
      try {
        const { data } = await axios.post('/api/v1/auth/profile', { nombre, email });
        // Sanctum response: data.data contains updated user object
        const payload = data?.data ?? data?.datos ?? data;
        // payload can be the updated user object
        this.user = payload || this.user;
        return true;
      } catch (e) {
        this.error = e?.response?.data?.message || e?.response?.data?.mensaje || 'No se pudo actualizar el perfil';
        return false;
      } finally { this.loading = false; }
    },
    async uploadAvatar(file){
      this.loading = true; this.error = null;
      try {
        const form = new FormData();
        form.append('avatar', file);
        const { data } = await axios.post('/api/v1/auth/avatar', form, { headers: { 'Content-Type': 'multipart/form-data' } });
        // Sanctum response: data.data contains { ruta_imagen }
        const payload = data?.data ?? data?.datos ?? data;
        const ruta = payload?.ruta_imagen || payload?.ruta;
        if (ruta) {
          const cacheBust = `${ruta}${ruta.includes('?') ? '&' : '?'}t=${Date.now()}`;
          this.user = { ...(this.user || {}), ruta_imagen: cacheBust };
        }
        return ruta || true;
      } catch (e) {
        this.error = e?.response?.data?.message || e?.response?.data?.mensaje || 'No se pudo subir la imagen';
        return false;
      } finally { this.loading = false; }
    },
    async logout(){
      try {
        await axios.post('/api/v1/auth/logout');
      } catch (e) {
        console.error('Error during logout:', e);
      }
      this.user = null;
      this.token = null;
      localStorage.removeItem('invx_token');
      window.location.href = '/'; // Redirigir a landing page en lugar de login
    },
    
    // Utility method to manually set/fix user role
    setUserRole(role) {
      if (!this.user) {
        console.warn('Cannot set role: No user is logged in');
        return false;
      }
      
      console.log(`Setting user role to ${role} for user ID ${this.user.id}`);
      this.user = { 
        ...this.user, 
        rol: role,
        role: role
      };
      return true;
    }
  }
});
