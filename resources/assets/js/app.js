
/**
 * We will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('stack-item-list', require('./components/ItemList.vue'));
Vue.component('stack-file-item', require('./components/FileItem.vue'));
Vue.component('stack-folder-item', require('./components/FolderItem.vue'));

const app = new Vue({
    el: '#app'
});
