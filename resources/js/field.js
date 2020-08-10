Nova.booting((Vue, router, store) => {
  Vue.component('index-laravel-nova-many-to-one-polymorphic-relationship', require('./components/IndexField'))
  Vue.component('detail-laravel-nova-many-to-one-polymorphic-relationship', require('./components/DetailField'))
  Vue.component('form-laravel-nova-many-to-one-polymorphic-relationship', require('./components/FormField'))
})
