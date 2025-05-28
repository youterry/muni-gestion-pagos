import { api } from "src/boot/axios";

class CursoService {
    static async getData(params) {
        return (await api.get('/api/cursos',params)).data;
    }

    static async get(id) {
        return (await api.get(`/api/cursos/${id}`)).data;
    }

    static async delete(id) {
        return (await api.delete(`/api/cursos/${id}`));
    }

    static async save(reg) {
        if (reg.id === undefined || reg.id === null) {
            return (await api.post("api/cursos", reg)).data;
        } else {
            return (await api.put(`api/cursos/${reg.id}`, reg)).data;
        }
    }
}

export default CursoService;