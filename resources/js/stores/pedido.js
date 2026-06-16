import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const usePedidoStore = defineStore('pedido', {
  state: () => ({
    items: [],
    loading: false,
    error: null,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
    }
  }),
  
  actions: {
    async fetch(page = 1) {
      this.loading = true; 
      this.error = null;
      try {
        // Petición con parámetro page usando params para mayor claridad
        const { data } = await axios.get(`${apiBase}/pedidos`, { params: { page } });
        // Soportar varios envoltorios: { datos: { data, current_page... } } o directamente { data, current_page... }
        const payload = (data && data.datos) ? data.datos : data;
        // Si viene un array plano, convertirlo a estructura paginada mínima
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
        return this.items;
      } catch (e) {
        console.error('Error fetching pedidos:', e);
        this.error = 'No se pudo cargar pedidos';
        throw e;
      } finally {
        this.loading = false;
      }
    },
    
    async remove(id) {
      this.loading = true;
      this.error = null;
      
      // Store original items before attempting deletion
      const originalItems = [...this.items];
      
      try {
        console.log(`Intentando eliminar pedido con ID: ${id}`);
        
        // NO MORE OPTIMISTIC UPDATES - Only delete locally after successful server response
        await axios.delete(`${apiBase}/pedidos/${id}`);
        console.log(`Pedido con ID ${id} eliminado con éxito en el servidor`);
        
        // Now that server confirmed deletion, update local state
        const index = this.items.findIndex(item => 
          (item.id_pedido || item.id) == id
        );
        
        if (index !== -1) {
          this.items.splice(index, 1);
          // También actualizar el total
          if (this.pagination.total > 0) {
            this.pagination.total--;
          }
          console.log('Eliminado con éxito del estado local');
        } else {
          console.warn(`No se encontró pedido con ID ${id} en el estado local`);
        }
        
        return true;
      } catch (e) {
        console.error('Error completo al eliminar el pedido:', e);
        console.error('Detalles de la respuesta:', {
          status: e.response?.status,
          statusText: e.response?.statusText,
          data: e.response?.data,
          headers: e.response?.headers
        });
        
        // Extraer mensaje de error del servidor
        this.error = 
          e.response?.data?.mensaje || 
          e.response?.data?.message || 
          e.response?.data?.error || 
          (e.response?.status === 500 ? 'Error interno del servidor' : 'No se pudo eliminar el pedido');
          
        // Información adicional sobre restricciones
        if (e.response?.status === 500) {
          console.warn('El error 500 podría indicar una violación de restricción de integridad en la base de datos');
          console.warn('Posibles tablas relacionadas: detalles_pedido, facturas, etc.');
        }
        
        throw e;
      } finally {
        this.loading = false;
      }
    },
    
    // Adding delete and destroy methods as aliases to remove for compatibility
    async delete(id) {
      console.log('Pedido delete method called with ID:', id);
      return this.remove(id);
    },
    
    async destroy(id) {
      console.log('Pedido destroy method called with ID:', id);
      return this.remove(id);
    }
  }

});
