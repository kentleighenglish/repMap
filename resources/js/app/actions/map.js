
const MAP_TYPES = {
	RECEIVE_CONSTITUENCIES: 'MAP@RECEIVE_CONSTITUENCIES',
	RESIZE_MAP: 'MAP@RESIZE_MAP'
}

const resizeMap = size => ({
	type: MAP_TYPES.RESIZE_MAP,
	size
});

module.exports = {
	MAP_TYPES,
	resizeMap
}
