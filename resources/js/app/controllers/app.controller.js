const { setActiveConstituency } = require('app/actions/filter');

class AppController {

	constructor($scope, $ngRedux) {

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);
	}

	mapStateToThis({ map: { constituencies }, filter }) {
		return {
			constituencies,
			filter
		}
	}

	mapDispatchToThis(dispatch) {
		return {
			setActiveConstituency: (id) => dispatch(setActiveConstituency(id))
		}
	}

	onConstituencyChange() {
		this.setActiveConstituency(this.filter.activeConstituency);
	}

}

module.exports = ['$scope', '$ngRedux', AppController];
