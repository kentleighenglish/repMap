// const {} = require('app/actions/filter');
const { reduce } = require('lodash');

class AppController {

	constructor($scope, $ngRedux) {

		$ngRedux.connect(this.mapStateToThis, this.mapDispatchToThis)(this);
	}

	mapStateToThis({ map: { constituencies, counties, parties }, filter }) {
		return {
			members: reduce(constituencies, (arr, c) => c.elected_member ? [ ...arr, c.elected_member ] : arr, []),
			counties,
			parties,
			filter
		}
	}

	mapDispatchToThis() {
		return {
		}
	}

}

module.exports = ['$scope', '$ngRedux', AppController];
