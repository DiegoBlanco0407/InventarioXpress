import { defineStore } from 'pinia';
import axios from 'axios';
const apiBase = '/api/v1';

export const useStockStore = defineStore('stock', {
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
        // Pide la página indicada con el parámetro
        const { data } = await axios.get(`${apiBase}/stock`, { params: { page } });
        // Asume que el backend responde algo tipo: { datos: { data: [...], current_page, last_page, per_page, total ... } }
        const payload = (data && data.datos) ? data.datos : data;
        const paginated = Array.isArray(payload)
          ? { data: payload, current_page: 1, last_page: 1, per_page: payload.length, total: payload.length }
          : (payload || {});
        
        this.items = Array.isArray(paginated.data) ? paginated.data : [];
        // Guarda los datos de paginación
        this.pagination = {
          current_page: Number(paginated.current_page) || Number(page) || 1,
          last_page: Number(paginated.last_page) || 1,
          per_page: Number(paginated.per_page) || (Array.isArray(paginated.data) ? paginated.data.length : 15),
          total: Number(paginated.total) || (Array.isArray(paginated.data) ? paginated.data.length : 0)
        };
        return this.items;
      } catch (e) {
        console.error('Error fetching stock:', e);
        this.error = 'No se pudo cargar stock';
        throw e;
      } finally {
        this.loading = false;
      }
    },
    
    async remove(id) {
      this.loading = true;
      this.error = null;
      try {
        // El stock es compuesto, así que id debe ser un objeto con id_almacen y id_material
        // O una cadena en formato "id_almacen:id_material"
        let id_almacen, id_material;
        
        if (typeof id === 'object') {
          id_almacen = id.id_almacen;
          id_material = id.id_material;
        } else if (typeof id === 'string' && id.includes(':')) {
          [id_almacen, id_material] = id.split(':');
        } else {
          throw new Error('ID de stock inválido, se esperaba objeto o formato "id_almacen:id_material"');
        }
        
        await axios.delete(`${apiBase}/stock/${id_almacen}/${id_material}`);
        
        // Eliminar del estado local INMEDIATAMENTE
        const index = this.items.findIndex(item => 
          (item.id_almacen == id_almacen && item.id_material == id_material)
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
        console.error('Error al eliminar el stock:', e);
        this.error = e.response?.data?.message || 'No se pudo eliminar el stock';
        throw e;
      } finally {
        this.loading = false;
      }
    }
  }
});
