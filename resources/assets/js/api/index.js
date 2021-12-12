import axios from 'axios';

export default {
    async search(string = '') {
        console.log('taaaaa')
        return await axios.get(`/api/providers?state=44&keywords=${string}`);
    }
}
