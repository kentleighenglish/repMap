
const FILTER_TYPES = {
	SET_ACTIVE_CONSTITUENCY: 'FILTER//SET_ACTIVE_CONSTITUENCY'
}

const setActiveConstituency = key => ({
	type: FILTER_TYPES.SET_ACTIVE_CONSTITUENCY,
	key
});

module.exports = {
	setActiveConstituency,
	FILTER_TYPES
}
