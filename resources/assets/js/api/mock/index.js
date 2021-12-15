import networkLinks from './data/networkLinks.json';
import Generator from './generator';

const SEARCH_KEYS_PROVIDER = ['first_name', 'last_name', 'full_name', 'full_name_md', 'locations', 'specialty', 'provider_type', 'specialty_synonyms'];
const SEARCH_KEYS_FACILITY = ['facility_name', 'locations'];

const fetch = (mockData, time = 0) => {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve(mockData)
        }, time)
    })
};


export default {
    async search(key, searchString) {
        let providers = await Generator.generateProviders();
        let facilities = await Generator.generateFacilities();

        let result = [];

        let providerType = key.trim('').split('_')[0];
        let searchBy = key.trim('').split('_')[1];

        if (key === 'search') {
            let providersArr = providers.filter(provider => this._isMatchDoctor(provider, searchString));
            let facilitiesArr = facilities.filter(facility => this._isMatchFacility(facility, searchString));

            result = this._azSort([
                ...providersArr,
                ...facilitiesArr
            ]);
        } else {
            if (providerType === 'provider') {
                result = this.getDoctorByKey(searchBy, searchString);
            } else if (providerType === 'facility') {
                result = this.getFacilityByKey(searchBy, searchString);
            }
        }

        return fetch(result, 1000);
    },

    async getDoctorByKey(key, queryString) {
        let providers = await Generator.generateProviders();

        let providersArr = [];

        if (key === 'name') {
            let docByLn = []; // last name group
            let docByFn = []; // first name group

            providers.forEach(provider => {
                if (provider.last_name.search(new RegExp(queryString, 'i')) >= 0) {
                    docByLn.push(provider);
                } else if (provider.first_name.search(new RegExp(queryString, 'i')) >= 0) {
                    docByFn.push(provider);
                }
            });

            providersArr = [
                ...docByLn,
                ...docByFn
            ]
        } else if (key === 'city') {
            providersArr = providers.filter(provider => this._locationMatch(provider.locations, queryString));
            providersArr = providersArr.map(provider => {
                provider.locations = this._locationsShift(provider.locations, queryString);
                return provider;
            });
            providersArr = this._azSort(providersArr);
        } else if (key === 'specialty') {
            providersArr = providers.filter(provider => {
                return provider[key].replace(/\((.*)\)/, "$1").search(new RegExp(queryString.replace(/\((.*)\)/, "$1"), 'i')) >= 0 || provider['specialty_synonyms'].replace(/\((.*)\)/, "$1").search(new RegExp(queryString.replace(/\((.*)\)/, "$1"), 'i')) >= 0;
            });
        } else {
            providersArr = providers.filter(provider => {
                return provider[key].replace(/\((.*)\)/, "$1").search(new RegExp(queryString.replace(/\((.*)\)/, "$1"), 'i')) >= 0;
            });
        }

        return providersArr;
    },

    async getFacilityByKey(key, queryString) {
        let facilities = await Generator.generateFacilities();

        let data = [];

        if (key === 'services') {
            data = facilities.filter(facility => {
                return facility.locations.some(location => location.services.replace(/\((.*)\)/, "$1").search(new RegExp(queryString.replace(/\((.*)\)/, "$1"), 'i')) >= 0);
            });
        } else if (key === 'city') {
            data = facilities.filter(facility => this._locationMatch(facility.locations, queryString));
            data = data.map(facility => {
                facility.locations = this._locationsShift(facility.locations, queryString);
                return facility;
            });
        } else if (key === 'facility_services') {
            data = this.getFacilityByKey('services', queryString);
        } else {
            data = facilities.filter(facility => {
                return facility[key].search(new RegExp(queryString, 'i')) >= 0;
            });
        }

        return this._azSort(data);
    },

    async getDoctor(id) {
        let providers = await Generator.generateProviders();
        return providers.find(provider => provider.npi == id);
    },

    async getFacility(id) {
        let facilities = await Generator.generateFacilities();
        return facilities.find(facility => facility.tin == id);
    },

    async getItems(key) {
        let providers = await Generator.generateProviders();
        let facilities = await Generator.generateFacilities();

        let data = [];

        let providerType = key.trim('').split('_')[0];
        let searchBy = key.trim('').split('_')[1];
        if (providerType === 'provider') {
            if (searchBy === 'city') {
                providers.forEach(provider => {
                    provider.locations.forEach(loc => {
                        data.push(loc.city);
                    });
                });
            } else {
                data = providers.map(item => item[searchBy]);
            }
        } else if (providerType === 'facility') {
            if (searchBy === 'city' || searchBy === 'services') {
                facilities.forEach(provider => {
                    provider.locations.forEach(loc => {
                        if (loc.state === "AL") {
                            data.push(...loc[searchBy].split(','));
                        }
                        data = data.map(loc => loc.trim());
                    });
                });
            } else {
                data = facilities.map(item => item[searchBy]);
            }
        }

        data = data.filter((item, index) => data.indexOf(item) === index).sort(function(a, b){
            if(a < b) { return -1; }
            if(a > b) { return 1; }
            return 0;
        });

        return fetch(data, 1000);
    },

    getLinks() {
        return fetch(networkLinks || [], 0);
    },

    _azSort(array) {
        return array.sort(function(a, b){
            let keyA = a.type === 'provider' ? 'first_name' : 'facility_name';
            let keyB = b.type === 'provider' ? 'first_name' : 'facility_name';

            if(a[keyA] < b[keyB]) { return -1; }
            if(a[keyA] > b[keyB]) { return 1; }
            return 0;
        })
    },

    _isMatchDoctor(object, query) {
        return SEARCH_KEYS_PROVIDER.some(key => {
            if (key === 'locations') {
                return this._locationMatch(object.locations, query);
            } else {
                return typeof object[key] === 'string' ? object[key].search(new RegExp(query, 'i')) >= 0 : false;
            }
        });
    },

    _isMatchFacility(object, query) {
        return SEARCH_KEYS_FACILITY.some(key => {
            if (key === 'locations') {
                return this._locationMatch(object.locations, query);
            } else {
                return typeof object[key] === 'string' ? object[key].search(new RegExp(query, 'i')) >= 0 : false;
            }
        });
    },

    _locationMatch(locations = [], query) {
        return locations.some(location => {
            for (const [key, value] of Object.entries(location)) {
                if (value.toString().search(new RegExp(query, 'i')) >= 0) {
                    return true
                }
            }
        });
    },

    _locationsShift(locations = [], query = '') {
        locations.forEach((location, index) => {
            for (const [key, value] of Object.entries(location)) {
                if (value.toString().search(new RegExp(query, 'i')) >= 0) {
                    locations.unshift(...locations.splice(index, 1))
                    return false;
                }
            }
        });
        return locations;
    }
}
