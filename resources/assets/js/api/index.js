import axios from 'axios';

export default {
    async search(query = '') {
        return await axios.get(`/api/providers`, {
            params: query
        });
    },

    async getProvider(id) {
        return await axios.get(`/api/providers/${id}`);
    }
}
