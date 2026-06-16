import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const Login = () => import('../views/Login.vue');
const Register = () => import('../views/Register.vue');
const AuthHome = () => import('../views/AuthHome.vue');
const Profile = () => import('../views/Profile.vue');
const Dashboard = () => import('../views/Dashboard.vue');
const AlmacenList = () => import('../views/almacenes/AlmacenList.vue');
const AlmacenCreate = () => import('../views/almacenes/AlmacenCreate.vue');
const AlmacenEdit = () => import('../views/almacenes/AlmacenEdit.vue');
const MaterialList = () => import('../views/materiales/MaterialList.vue');
const MaterialCreate = () => import('../views/materiales/MaterialCreate.vue');
const MaterialEdit = () => import('../views/materiales/MaterialEdit.vue');
const PedidoList = () => import('../views/pedidos/PedidoList.vue');
const PedidoCreate = () => import('../views/pedidos/PedidoCreate.vue');
const PedidoEdit = () => import('../views/pedidos/PedidoEdit.vue');
const StockList = () => import('../views/stock/StockList.vue');
const StockCreate = () => import('../views/stock/StockCreate.vue');
const StockEdit = () => import('../views/stock/StockEdit.vue');
const CalleList = () => import('../views/calles/CalleList.vue');
const CalleCreate = () => import('../views/calles/CalleCreate.vue');
const CalleEdit = () => import('../views/calles/CalleEdit.vue');
const CiudadList = () => import('../views/ciudades/CiudadList.vue');
const CiudadCreate = () => import('../views/ciudades/CiudadCreate.vue');
const CiudadEdit = () => import('../views/ciudades/CiudadEdit.vue');
const TramoList = () => import('../views/tramos/TramoList.vue');
const TramoCreate = () => import('../views/tramos/TramoCreate.vue');
const TramoEdit = () => import('../views/tramos/TramoEdit.vue');
const TramoCalleList = () => import('../views/tramoCalle/TramoCalleList.vue');
const TramoCalleCreate = () => import('../views/tramoCalle/TramoCalleCreate.vue');
const TramoCalleEdit = () => import('../views/tramoCalle/TramoCalleEdit.vue');
const UsuarioList = () => import('../views/usuarios/UsuarioList.vue');
const UsuarioCreate = () => import('../views/usuarios/UsuarioCreate.vue');
const UsuarioEdit = () => import('../views/usuarios/UsuarioEdit.vue');
const InstalacionList = () => import('../views/instalaciones/InstalacionList.vue');
const InstalacionCreate = () => import('../views/instalaciones/InstalacionCreate.vue');
const InstalacionEdit = () => import('../views/instalaciones/InstalacionEdit.vue');
const SalidaList = () => import('../views/salidas/SalidaList.vue');
const SalidaCreate = () => import('../views/salidas/SalidaCreate.vue');
const SalidaEdit = () => import('../views/salidas/SalidaEdit.vue');
const Unauthorized = () => import('../views/Unauthorized.vue');
const NotFound = () => import('../views/NotFound.vue');
const LandingPage = () => import('../views/LandingPage.vue');
const ContactoPage = () => import('../views/ContactoPage.vue');
const TerminosPage = () => import('../views/TerminosPage.vue');
const PrivacidadPage = () => import('../views/PrivacidadPage.vue');

const routes = [
  { path: '/', name: 'home', component: LandingPage, meta: { public: true } },
  { path: '/login', name: 'login', component: Login, meta: { public: true } },
  { path: '/register', name: 'register', component: Register, meta: { public: true } },
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/perfil', name: 'perfil', component: Profile },
  { path: '/almacenes', name: 'almacenes', component: AlmacenList },
  { path: '/almacenes/crear', name: 'almacen-crear', component: AlmacenCreate },
  { path: '/materiales', name: 'materiales', component: MaterialList },
  { path: '/materiales/crear', name: 'material-crear', component: MaterialCreate },
  { path: '/pedidos', name: 'pedidos', component: PedidoList },
  { path: '/pedidos/crear', name: 'pedido-crear', component: PedidoCreate },
  { path: '/pedidos/editar/:id', name: 'pedido-editar', component: PedidoEdit, meta: { requiresEditor: true } },
  { path: '/stock', name: 'stock', component: StockList },
  { path: '/stock/crear', name: 'stock-crear', component: StockCreate },
  { path: '/stock/editar/:id_almacen/:id_material', name: 'stock-editar', component: StockEdit, meta: { requiresEditor: true } },
  { path: '/calles', name: 'calles', component: CalleList },
  { path: '/calles/crear', name: 'calle-crear', component: CalleCreate },
  { path: '/ciudades', name: 'ciudades', component: CiudadList },
  { path: '/ciudades/crear', name: 'ciudad-crear', component: CiudadCreate },
  { path: '/tramos', name: 'tramos', component: TramoList },
  { path: '/tramos/crear', name: 'tramo-crear', component: TramoCreate },
  { path: '/tramo-calle', name: 'tramo-calle', component: TramoCalleList },
  { path: '/tramo-calle/crear', name: 'tramo-calle-crear', component: TramoCalleCreate },
  { path: '/almacenes/editar/:id', name: 'almacen-editar', component: AlmacenEdit, meta: { requiresEditor: true } },
  { path: '/materiales/editar/:id', name: 'material-editar', component: MaterialEdit, meta: { requiresEditor: true } },
  { path: '/ciudades/editar/:id', name: 'ciudad-editar', component: CiudadEdit, meta: { requiresEditor: true } },
  { path: '/calles/editar/:id', name: 'calle-editar', component: CalleEdit, meta: { requiresEditor: true } },
  { path: '/tramos/editar/:id', name: 'tramo-editar', component: TramoEdit, meta: { requiresEditor: true } },
  { path: '/tramo-calle/editar/:id', name: 'tramo-calle-editar', component: TramoCalleEdit, meta: { requiresEditor: true } },
  { path: '/instalaciones/editar/:id_tramo/:id_material/:id_almacen', name: 'instalacion-editar', component: InstalacionEdit, meta: { requiresEditor: true } },
  { path: '/salidas/editar/:id', name: 'salida-editar', component: SalidaEdit, meta: { requiresEditor: true } },
  { path: '/usuarios', name: 'usuarios', component: UsuarioList, meta: { requiresAdmin: true } },
  { path: '/usuarios/crear', name: 'usuario-crear', component: UsuarioCreate, meta: { requiresAdmin: true } },
  { path: '/usuarios/editar/:id', name: 'usuario-editar', component: UsuarioEdit, meta: { requiresAdmin: true } },
  { path: '/instalaciones', name: 'instalaciones', component: InstalacionList },
  { path: '/instalaciones/crear', name: 'instalacion-crear', component: InstalacionCreate },
  { path: '/salidas', name: 'salidas', component: SalidaList },
  { path: '/salidas/crear', name: 'salida-crear', component: SalidaCreate },
  { path: '/contacto', name: 'contacto', component: ContactoPage, meta: { public: true } },
  { path: '/terminos', name: 'terminos', component: TerminosPage, meta: { public: true } },
  { path: '/privacidad', name: 'privacidad', component: PrivacidadPage, meta: { public: true } },
  { path: '/no-autorizado', name: 'unauthorized', component: Unauthorized, meta: { public: true } },
  { path: '/:pathMatch(.*)*', name: 'not-found', component: NotFound, meta: { public: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Forzar el desplazamiento al inicio inmediatamente
    if (to.path !== from.path) {
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0; // Para Safari
      return { left: 0, top: 0 };
    }
  },
});

router.beforeEach(async (to) => {
  const auth = useAuthStore();
  if (!auth.user && auth.token) {
    try { await auth.initialize(); } catch {}
  }
  const isPublic = to.meta?.public === true;

  // Authenticated users should not see auth pages (but can see landing page)
  if (auth.isAuthenticated && (to.name === 'login' || to.name === 'register')) {
    // Si están autenticados y van a login/register, llevarlos al dashboard
    return { path: '/dashboard' };
  }

  // Block private routes when not authenticated
  if (!isPublic && !auth.isAuthenticated) {
    // Redirigir a la landing page en lugar de login
    return { path: '/', query: { redirect: to.fullPath } };
  }

  // Block all create routes for non-editors (rol 1 or 2) except usuarios which has its own guard
  const isCreateRoute = to.path.endsWith('/crear') || String(to.name || '').endsWith('-crear');
  if (isCreateRoute && !to.meta?.requiresAdmin) { // Si ya tiene requiresAdmin, se maneja después
    const raw = auth.user?.rol ?? auth.user?.role;
    const rnum = Number(raw);
    const isEditor = auth.isAuthenticated && (rnum === 1 || rnum === 2 || ['admin','jefe'].includes(String(raw).toLowerCase()));
    if (!isEditor) {
      // Redirect to Unauthorized with a link back to the list route
      const parts = to.path.split('/').filter(Boolean);
      const back = '/' + parts.slice(0, -1).join('/');
      return { path: '/no-autorizado', query: { redirect: back || '/dashboard' } };
    }
  }

  // Block routes that explicitly require admin
  if (to.meta?.requiresAdmin) {
    const raw = auth.user?.rol ?? auth.user?.role;
    const rnum = Number(raw);
    const isAdmin = auth.isAuthenticated && (rnum === 1 || String(raw).toLowerCase() === 'admin');
    if (!isAdmin) {
      return { path: '/no-autorizado', query: { redirect: '/dashboard' } };
    }
  }

  // Block routes that explicitly require editor (roles 2 or 3)
  if (to.meta?.requiresEditor) {
    const raw = auth.user?.rol ?? auth.user?.role;
    const rnum = Number(raw);
    const isEditor = auth.isAuthenticated && (rnum === 1 || rnum === 2 || ['admin','jefe'].includes(String(raw).toLowerCase()));
    if (!isEditor) {
      return { path: '/no-autorizado', query: { redirect: '/dashboard' } };
    }
  }

  return true;
});

export default router;

