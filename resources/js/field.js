Nova.booting((Vue, router, store) => {
  Vue.component('index-laravel-nova-morph-many-to-one', require('./components/IndexField'))
  Vue.component('detail-laravel-nova-morph-many-to-one', require('./components/DetailField'))
  Vue.component('form-laravel-nova-morph-many-to-one', require('./components/FormField'))
})
