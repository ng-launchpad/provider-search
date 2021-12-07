import axios from 'axios';

function formatTelephone(phone) {
    let format = phone.toString().replace(/[- )(]/g,'').split('');

    if (format.length) {
        format.splice(3, 0, '-');
        format.splice(7, 0, '-');
    }

    return format.join('');
}

function rebuildFacilities(facilitiesJson) {
    return facilitiesJson.map(facility => {
        let newObj = {
            ...facility,
            locations: [
                {
                    addr_line_1: facility.addr_line_1,
                    addr_line_2: facility.addr_line_2,
                    city: facility.city,
                    state: facility.state,
                    zip: facility.zip.toString().split('').slice(0, 5).join(''),
                    phone_number: formatTelephone(facility.phone_number),
                    services: facility.services
                }
            ],
            type: 'facility'
        }

        delete newObj.city;
        delete newObj.state;
        delete newObj.zip;
        delete newObj.phone_number;
        delete newObj.services;

        return newObj;
    })
}

// generating new array of objects for providers
function rebuildProviders(providersJson) {
    return providersJson.map(provider => {
        let newObj = {
            ...provider,
            full_name: `${provider.first_name} ${provider.last_name}`,
            full_name_md: `${provider.first_name} ${provider.middle_name} ${provider.last_name}`,
            locations: [
                {
                    address_type: provider.address_type,
                    addr_line_1: provider.practice_addr_line_1,
                    addr_line_2: provider.practice_addr_line_2,
                    city: provider.practice_city,
                    state: provider.practice_state,
                    zip: provider.practice_zip.toString().split('').slice(0, 5).join(''),
                    practice_phone: formatTelephone(provider.practice_phone),
                    practice_name: provider.practice_name
                }
            ],
            type: 'provider'
        }

        delete newObj.address_type;
        delete newObj.practice_addr_line_1;
        delete newObj.practice_addr_line_2;
        delete newObj.practice_city;
        delete newObj.practice_state;
        delete newObj.practice_zip;
        delete newObj.practice_phone;

        return newObj;
    });
}

function groupByKey(array, key) {
    const grouped = new Map();
    array.forEach((item) => {
        const propertyValue = item[key];
        if (grouped.has(propertyValue)) {
            grouped.get(propertyValue).locations = grouped.get(propertyValue).locations.concat(item.locations).sort(function(x,y){ return x.address_type === "Primary" ? -1 : y.address_type === "Primary" ? 1 : 0; });
            grouped.set(propertyValue, {
                ...grouped.get(propertyValue)
            });
        } else {
            grouped.set(propertyValue, item);
        }
    });

    return Array.from(grouped, ([key, value]) => ({ ...value }));
}

export default {
    generateFacilities: async function() {
        const {data} = await axios.get('/mock-data/facilities.json')

        let facilities = rebuildFacilities(data);

        return groupByKey(facilities, 'tin');
    },

    generateProviders: async function() {
        const {data} = await axios.get('/mock-data/providers.json')

        let providers = rebuildProviders(data);

        return groupByKey(providers, 'npi');
    }
}
