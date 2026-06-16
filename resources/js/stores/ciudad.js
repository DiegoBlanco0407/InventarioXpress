import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const useCiudadStore = defineStore('ciudad', {
  state: () => ({
    items: [],
    loading: false,
    error: null,
    pagination: { current_page: 1, last_page: 1, per_page: 15, total: 0 }
  }),
  actions: {
    async fetch(page = 1) {
      this.loading = true; this.error = null;
      try {
        const { data } = await axios.get(`${apiBase}/ciudades`, { params: { page } });
        const payload = data?.datos ?? data;
        const paginated = Array.isArray(payload)
          ? { data: payload, current_page: 1, last_page: 1, per_page: payload.length, total: payload.length }
          : (payload || {});
        this.items = Array.isArray(paginated.data) ? paginated.data : [];
        this.pagination = {
          current_page: Number(paginated.current_page) || Number(page) || 1,
          last_page: Number(paginated.last_page) || 1,
          per_page: Number(paginated.per_page) || (Array.isArray(paginated.data) ? paginated.data.length : 15),
          total: Number(paginated.total) || (Array.isArray(paginated.data) ? paginated.data.length : 0)
        };
      } catch (e) { this.error = 'No se pudo cargar ciudades'; }
      finally { this.loading = false; }
    },
    async create(payload){
      // Acción utilizada por CiudadCreate.vue
      this.loading = true; this.error = null;
      try {
        await axios.post(`${apiBase}/ciudades`, payload);
        await this.fetch(this.pagination.current_page || 1);
      } catch(e){
        this.error = 'No se pudo crear la ciudad';
        throw e;
      } finally { this.loading = false; }
    },
    
    async remove(id) {
      this.loading = true;
      this.error = null;
      try {
        // El token se maneja automáticamente mediante interceptores de axios
        await axios.delete(`${apiBase}/ciudades/${id}`);
        
        // Eliminar del estado local INMEDIATAMENTE
        const index = this.items.findIndex(item => 
          (item.id_ciudad || item.id) == id
        );
        
        if (index !== -1) {
          this.items.splice(index, 1);
          // También actualizar el total
          if (this.pagination.total > 0) {
            this.pagination.total--;
          }
        }
        
        return true;
      } catch (e) {
        // Gestión de errores (se manejan las autorizaciones globalmente mediante interceptores)
        console.error('Error al eliminar la ciudad:', e);
        
        // Extraer mensaje de error del servidor
        this.error = 
          e.response?.data?.mensaje || 
          e.response?.data?.message || 
          e.response?.data?.error || 
          'No se pudo eliminar la ciudad';
          
        throw e;
      } finally {
        this.loading = false;
      }
    }
  }
});

