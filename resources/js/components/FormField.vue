<script>
	import {FormField, HandlesValidationErrors} from 'laravel-nova'

	export default {
		mixins: [FormField, HandlesValidationErrors],

        props: ['resourceName', 'resourceId', 'field'],

        mounted: function () {
            console.log(this.resourceName, this.resourceId, this.field, this.value);
        },

		computed: {

			/**
			 * Do not show the type select option if this is the edit screen
			 * And we don't want the user to change the polymorphic type.
			 */
			shouldShowTypeSelect() {
				return !(this.resourceId && this.field.hideTypeWhenUpdating)
			},

			/**
			 * Do not show the type select option if this is the edit screen
			 * And we don't want the user to change the polumorphic type.
			 */
			shouldDisableTypeSelect() {
				return this.resourceId && this.field.disableTypeWhenUpdating
			},

		},

		methods: {

			fill(formData) {
				formData.append(this.field.attribute, this.value)

				this.$children.forEach(component => {
					if(component.field.attribute !== this.field.attribute) {
						component.field.fill(formData);
					}
				})
			}

		}
	}
</script>

<template>
	<div>

		<default-field :field="field" v-show="shouldShowTypeSelect">
			<template slot="field">
                <!-- TODO: refactor our reference to ID and NAME attributes -->
				<select
					:id="field.attribute"
					v-model="value"
					class="w-full form-control form-select"
					:class="errorClasses"
					:disabled="shouldDisableTypeSelect"
				>
					<option
						v-for="option in field.options"
                        v-bind:key="field.attribute + '-option-' + option.id"
                        v-bind:value="option.id"
                        v-text="option.name"
					></option>
				</select>

				<p v-if="hasError" class="my-2 text-danger">
					{{ firstError }}
				</p>
			</template>
		</default-field>
	</div>
</template>
