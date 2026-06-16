import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const useMaterialStore = defineStore('material', {
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
      this.loading = true; this.error = null;
      try {
        const { data } = await axios.get(`${apiBase}/materiales`, { params: { page } });
        const payload = (data && data.datos) ? data.datos : data;
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
      }
      catch (e) { this.error = 'No se pudo cargar materiales'; }
      finally { this.loading = false; }
    },
    
    async remove(id) {
      this.loading = true;
      this.error = null;
      try {
        // Call the API to delete the material
        await axios.delete(`${apiBase}/materiales/${id}`);
        
        // Remove from local state IMMEDIATELY (optimistic update)
        const index = this.items.findIndex(item => 
          (item.id_material || item.id) == id
        );
        
        if (index !== -1) {
          this.items.splice(index, 1);
          // Also update the total
          if (this.pagination.total > 0) {
            this.pagination.total--;
          }
        }
        
        return true;
      } catch (e) {
        // Error handling
        console.error('Error al eliminar el material:', e);
        
        // Extract error message from server
        this.error = 
          e.response?.data?.mensaje || 
          e.response?.data?.message || 
          e.response?.data?.error || 
          'No se pudo eliminar el material';
          
        throw e;
      } finally {
        this.loading = false;
      }
    },
    
    // Adding delete and destroy methods as aliases to remove for compatibility
    async delete(id) {
      console.log('Material delete method called with ID:', id);
      try {
        const result = await this.remove(id);
        console.log('Delete operation successful');
        return result;
      } catch (error) {
        console.error('Error in delete method:', error);
        throw error;
      }
    },
    
    async destroy(id) {
      return this.remove(id);
    }
  }
});
