import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const useTramoStore = defineStore('tramo', {
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
        const { data } = await axios.get(`${apiBase}/tramos`, { params: { page } });
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
      } catch (e) { this.error = 'No se pudo cargar tramos'; }
      finally { this.loading = false; }
    },
    
    async delete(id) {
      this.loading = true;
      this.error = null;
      try {
        await axios.delete(`${apiBase}/tramos/${id}`);
        
        // Eliminar del estado local INMEDIATAMENTE
        const index = this.items.findIndex(item => 
          (item.id_tramo || item.id_tramos || item.id) == id
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
        console.error('Error al eliminar tramo:', e);
        this.error = e.response?.data?.message || 'No se pudo eliminar el tramo';
        throw e;
      } finally {
        this.loading = false;
      }
    }
  }
});
