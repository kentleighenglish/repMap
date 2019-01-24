
const FILTER_TYPES = {
	SET_ACTIVE_CONSTITUENCY: 'FILTER//SET_ACTIVE_CONSTITUENCY'
}

const setActiveConstituency = (id) => ({
	type: FILTER_TYPES.SET_ACTIVE_CONSTITUENCY,
	id
});

module.exports = {
	setActiveConstituency,
	FILTER_TYPES
}
