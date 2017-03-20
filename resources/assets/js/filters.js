import Vue from 'vue'
import moment from 'moment'

	Vue.filter('formatDate', function(value, format){

		if (value) {

			return moment(String(value)).format(format)
		}
	})

	Vue.filter('phone', function(value){

		if (value) {
			var cod = value.substring(0,4);
			var number = value.substring(4,11);
			var phone = cod + '-' + number;
			return phone;
		}
	})