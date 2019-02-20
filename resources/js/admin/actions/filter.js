
const FILTER_TYPES = {
	SET_ACTIVE_CONSTITUENCY: 'FILTER@SET_ACTIVE_CONSTITUENCY',
	SET_ACTIVE_COUNTY: 'FILTER@SET_ACTIVE_COUNTY',
	SET_ACTIVE_PARTY: 'FILTER@SET_ACTIVE_PARTY',
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

module.exports = {
	setActiveConstituency,
	setActiveCounty,
	setActiveParty,
	FILTER_TYPES
}
