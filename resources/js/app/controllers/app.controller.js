const { setActiveConstituency, setActiveCounty, setActiveParty, resetFilter } = require('app/actions/filter');
const { cloneDeep } = require('lodash');

class AppController {

	constructor($scope, $ngRedux) {

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);
	}

	mapStateToThis({ map: { constituencies, counties, parties }, filter }) {
		return {
			constituencies,
			counties,
			parties,
			filter: cloneDeep(filter)
		}
	}

	mapDispatchToThis(dispatch) {
		return {
			setActiveConstituency: (id) => dispatch(setActiveConstituency(id)),
			setActiveCounty: (id) => dispatch(setActiveCounty(id)),
			setActiveParty: (id) => dispatch(setActiveParty(id)),
			resetFilter: () => dispatch(resetFilter())
		}
	}

	onConstituencyChange() {
		this.setActiveConstituency(this.filter.activeConstituency);
	}

	onCountyChange() {
		this.setActiveCounty(this.filter.activeCounty);
	}

	onPartyChange() {
		this.setActiveParty(this.filter.activeParty);
	}

}

module.exports = ['$scope', '$ngRedux', AppController];
