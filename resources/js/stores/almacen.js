import { defineStore } from 'pinia';
import axios from 'axios';

const apiBase = '/api/v1';

export const useAlmacenStore = defineStore('almacen', {
  state: () => ({
    items: [],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 15,
      total: 0,
    },
    loading: false,
    error: null,
  }),

  actions: {
    async fetch(page = 1) {
      this.loading = true;
      this.error = null;
      try {
        const { data } = await axios.get(`${apiBase}/almacenes`, { params: { page } });
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
        return this.items;
      } catch (e) {
        console.error('Error fetching almacenes:', e);
        this.error = 'No se pudo cargar almacenes';
        throw e;
      } finally {
        this.loading = false;
      }
    },

    async remove(id) {
      this.loading = true;
      this.error = null;
      try {
        await axios.delete(`${apiBase}/almacenes/${id}`);
        
        // Eliminar del estado local INMEDIATAMENTE
        const index = this.items.findIndex(item => 
          (item.id_almacen || item.id) == id
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
        console.error('Error al eliminar el almacén:', e);
        this.error = e.response?.data?.message || 'No se pudo eliminar el almacén';
        throw e;
      } finally {
        this.loading = false;
      }
    },
  },
});
