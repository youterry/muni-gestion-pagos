// src/services/PagoService.js
import { api } from 'src/boot/axios';

const ENDPOINT = '/api/pagos';

class PagoService {
  static async getData(params = {}) {
    try {
      const response = await api.get(ENDPOINT, params);
      return response.data;
    } catch (error) {
      console.error('Error al obtener pagos:', error);
      throw error;
    }
  }

  static async get(id) {
    try {
      const response = await api.get(`${ENDPOINT}/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Error al obtener pago con ID ${id}:`, error);
      throw error;
    }
  }

  static async create(data) {
    try {
      const response = await api.post(ENDPOINT, data.pago);
      return response.data;
    } catch (error) {
      console.error('Error al crear pago:', error);
      throw error;
    }
  }

  static async update(id, data) {
    try {
      const response = await api.put(`${ENDPOINT}/${id}`, data.pago);
      return response.data;
    } catch (error) {
      console.error(`Error al actualizar pago con ID ${id}:`, error);
      throw error;
    }
  }

  static async delete(id) {
    try {
      const response = await api.delete(`${ENDPOINT}/${id}`);
      return response.data;
    } catch (error) {
      console.error(`Error al eliminar pago con ID ${id}:`, error);
      throw error;
    }
  }
}

export default PagoService;
