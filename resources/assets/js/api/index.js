import axios from 'axios';

export default {
    async search(query = '') {
        return await axios.get(`/api/providers?state_id=${window.state.id}`, {
            params: query
        });
    },

    async getProvider(id) {
        return await axios.get(`/api/providers/${id}`);
    },

    async getCities(id) {
        return await axios.get(`/api/cities?state_id=${window.state.id}`, {
            params: {
                network_id: id
            }
        });
    },

    async getSpecialities(id) {
        return await axios.get(`/api/specialities?state_id=${window.state.id}`, {
            params: {
                network_id: id
            }
        });
    }
}
