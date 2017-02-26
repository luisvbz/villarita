import Vue from 'vue';
import router from '../app';


export default {

	getAll (){
		return Vue.http.get('/workers');
	}
}