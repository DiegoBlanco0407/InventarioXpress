import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const useUsuarioStore = defineStore('usuario', {
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
        const { data } = await axios.get(`${apiBase}/usuarios`, { params: { page } });
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
      } catch (e) { this.error = 'No se pudo cargar usuarios'; }
      finally { this.loading = false; }
    },
    async delete(id){
      try {
        await axios.delete(`${apiBase}/usuarios/${id}`);
        // remove from local state optimistically
        this.items = (this.items || []).filter(u => String(u.id_usuario ?? u.id) !== String(id));
        // try to refetch current page to sync pagination
        try { await this.fetch(this.pagination.current_page || 1); } catch {}
      } catch (e) {
        throw e;
      }
    }
  }
});
