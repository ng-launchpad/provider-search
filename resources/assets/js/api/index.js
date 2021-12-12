import axios from 'axios';

export default {
    async search(query = '') {
        console.log('taaaaa')
        return await axios.get(`/api/providers?state=44&`, {
            params: query
        });
    }
}
