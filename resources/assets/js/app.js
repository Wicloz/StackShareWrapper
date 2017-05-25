
/**
 * Define Vue components.
 */

Vue.component('stack-item-list', require('./components/ItemList.vue'));
Vue.component('stack-file-item', require('./components/FileItem.vue'));
Vue.component('stack-folder-item', require('./components/FolderItem.vue'));

/**
 * Create a Vue event listener.
 */

window.Events = new class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, data = null) {
        this.vue.$emit(event, data);
    }

    listen(event, callback) {
        this.vue.$on(event, callback);
    }
};

/**
 * Attach a new root Vue instance to the page.
 */

const app = new Vue({
    el: '#app'
});
