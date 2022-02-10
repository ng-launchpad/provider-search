export default function(collection, size) {
    let result = [];

    // default size to two item
    size = parseInt(size) || 2;

    // add each chunk to the result
    for (let x = 0; x < Math.ceil(collection.length / size); x++) {
        let start = x * size;
        let end = start + size;

        result.push(collection.slice(start, end));
    }

    return result;
}
