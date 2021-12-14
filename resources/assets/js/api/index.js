import axios from 'axios';

export default {
    async search(query = '') {
        return await axios.get(`/api/providers`, {
            params: query
        });
    }
}
