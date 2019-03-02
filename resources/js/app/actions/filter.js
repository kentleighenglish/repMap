
const FILTER_TYPES = {
	SET_ACTIVE_CONSTITUENCY: 'FILTER@SET_ACTIVE_CONSTITUENCY',
	SET_ACTIVE_COUNTY: 'FILTER@SET_ACTIVE_COUNTY',
	SET_ACTIVE_PARTY: 'FILTER@SET_ACTIVE_PARTY',
	RESET_FILTER: 'FILTER@RESET_FILTER'
}

const setActiveConstituency = key => ({
	type: FILTER_TYPES.SET_ACTIVE_CONSTITUENCY,
	key
});

const setActiveCounty = key => ({
	type: FILTER_TYPES.SET_ACTIVE_COUNTY,
	key
});

const setActiveParty = key => ({
	type: FILTER_TYPES.SET_ACTIVE_PARTY,
	key
});

const resetFilter = () => ({
	type: FILTER_TYPES.RESET_FILTER
});

module.exports = {
	setActiveConstituency,
	setActiveCounty,
	setActiveParty,
	resetFilter,
	FILTER_TYPES
}
