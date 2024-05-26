@inject('coreConfigRepository', 'Webkul\Core\Repositories\CoreConfigRepository')

@php
    $nameKey = $item['key'] . '.' . $field['name'];

    $name = $coreConfigRepository->getNameField($nameKey);

    $validations = $coreConfigRepository->getValidations($field);

    $isRequired = Str::contains($validations, 'required') ? 'required' : '';

    $channelLocaleInfo = $coreConfigRepository->getChannelLocaleInfo($field, $currentChannel->code, $currentLocale->code);

    $field = collect([
        ...$field,
        'isVisible' => true,
    ])->map(function ($value, $key) use($coreConfigRepository) {
        if ($key == 'options') {
            return collect($coreConfigRepository->getOptions($value))->map(fn ($option) => [
                'title' => trans($option['title']),
                'value' => $option['value'],
            ])->toArray();
        }

        return $value;
    })->toArray();

    if (! empty($field['depends'])) {
        [$fieldName, $fieldValue] = explode(':' , $field['depends']);

        $dependNameKey = $item['key'] . '.' . $fieldName;
    }
@endphp

<input
    type="hidden"
    name="keys[]"
    value="{{ json_encode($item) }}"
/>

<v-configurable
    channel-count="{{ core()->getAllChannels()->count() }}"
    channel-locale="{{ $channelLocaleInfo }}"
    current-channel="{{ $currentChannel }}"
    current-locale="{{ $currentLocale }}"
    depend-name="{{ isset($field['depends']) ? $coreConfigRepository->getNameField($dependNameKey) : ''}}"
    field-data="{{ json_encode($field) }}"
    info="{{ trans($field['info'] ?? '') }}"
    is-require="{{ $isRequired }}"
    label="{{ trans($field['title']) }}"
    name="{{ $name }}"
    src="{{ Storage::url(core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)) }}"
    validations="{{ $validations }}"
    value="{{ core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? '' }}"
>
    <div class="mb-4">
        <div class="shimmer mb-1.5 h-4 w-24"></div>

        <div class="shimmer flex h-[42px] w-full rounded-md"></div>
    </div>
</v-configurable>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-configurable-template"
    >
        <x-admin::form.control-group class="last:!mb-0">
            <!-- Title of the input field -->
            <div    
                v-if="field.isVisible"
                class="flex justify-between"
            >
                <x-admin::form.control-group.label ::for="name">
                    @{{ label }} <span :class="isRequire"></span>

                    <span
                        v-if="field['channel_based'] && channelCount"
                        class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentChannel).name"
                    >
                    </span>
        
                    <span
                        v-if="field['locale_based']"
                        class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentLocale).name"
                    >
                    </span>
                </x-admin::form.control-group.label>
            </div>
        
            <!-- Text input -->
            <template v-if="field.type == 'text' && field.isVisible">
                <x-admin::form.control-group.control
                    type="text"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                />
            </template>
        
            <!-- Password input -->
            <template v-if="field.type == 'password' && field.isVisible">
                <x-admin::form.control-group.control
                    type="password"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                />
            </template>
        
            <!-- Number input -->
            <template v-if="field.type == 'number' && field.isVisible">
                <x-admin::form.control-group.control
                    type="number"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                    ::min="field.name == 'minimum_order_amount'"
                />
            </template>

            <!-- Color Input -->
            <template v-if="field.type == 'color' && field.isVisible">
                <v-field
                    v-slot="{ field, errors }"
                    :id="name"
                    :name="name"
                    :value="value != '' ? value : '#ffffff'"
                    :label="label"
                    :rules="validations"
                >
                    <input
                        type="color"
                        v-bind="field"
                        :class="[errors.length ? 'border border-red-500' : '']"
                        class="w-full appearance-none rounded-md border text-sm text-gray-600 transition-all hover:border-gray-400 dark:text-gray-300 dark:hover:border-gray-400"
                    />
                </v-field>
            </template>
        
            <!-- Textarea Input -->
            <template v-if="field.type == 'textarea' && field.isVisible">
                <x-admin::form.control-group.control
                    type="textarea"
                    class="text-gray-600 dark:text-gray-300"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                />
            </template>

            <!-- Textarea with tinymce -->
            <template v-if="field.type == 'editor' && field.isVisible">
                <x-admin::form.control-group.control
                    type="textarea"
                    class="text-gray-600 dark:text-gray-300"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                />
            </template>
        
            <!-- Select input -->
            <template v-if="field.type == 'select' && field.isVisible">
                <v-field
                    v-slot="data"
                    :id="name"
                    :name="name"
                    :rules="validations"
                    :value="value"
                    :label="label"
                >
                    <select
                        :id="name"
                        :name="name"
                        v-bind="data.field"
                        :class="[data.errors.length ? 'border border-red-500' : '']"
                        class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                    >
                        <option
                            v-for="option in field.options"
                            :value="option.value"
                            v-text="option.title"
                        >
                        </option>
                    </select>
                </v-field>
            </template>

            <!-- Multiselect Input -->
            <template v-if="field.type == 'multiselect' && field.isVisible">
                <v-field
                    v-slot="data"
                    :id="name"
                    :name="`${name}[]`"
                    :rules="validations"
                    :value="value"
                    :label="label"
                    multiple
                >
                    <select
                        :name="`${name}[]`"
                        v-bind="data.field"
                        :class="[data.errors.length ? 'border border-red-500' : '']"
                        class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        multiple
                    >
                        <option
                            v-for="option in field.options"
                            :value="option.value"
                            v-text="option.title"
                        >
                        </option>
                    </select>
                </v-field>
            </template>
           
            <!-- Boolean/Switch input -->
            <template v-if="field.type == 'boolean' && field.isVisible">
                <input
                    type="hidden"
                    :name="name"
                    value="0"
                />
        
                <label class="relative inline-flex cursor-pointer items-center">
                    <input  
                        type="checkbox"
                        :name="name"
                        :value="1"
                        :id="name"
                        class="peer sr-only"
                        :checked="parseInt(value)"
                    >

                    <div class="peer h-5 w-9 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-0.5 after:h-4 after:w-4 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none dark:border-gray-600 dark:bg-gray-700 rtl:peer-checked:after:-translate-x-full"></div>
                </label>
            </template>
        
            <template v-if="field.type == 'image' && field.isVisible">
                <div class="flex items-center justify-center">
                    <a
                        :href="src"
                        target="_blank"
                        v-if="value"
                    >
                        <img
                            :src="src"
                            :alt="name"
                            class="top-15 rounded-3 border-3 relative mr-5 h-[33px] w-[33px] border-gray-500"
                        />
                    </a>
                    
                    <x-admin::form.control-group.control
                        type="file"
                        ::name="name"
                        ::id="name"
                        ::rules="validations"
                        ::label="label"
                    />
                </div>
        
                <template v-if="value">
                    <x-admin::form.control-group class="mt-1.5 flex w-max cursor-pointer select-none items-center gap-1.5">
                        <x-admin::form.control-group.control
                            type="checkbox"
                            ::id="`${name}[delete]`"
                            ::name="`${name}[delete]`"
                            value="1"
                            ::for="`${name}[delete]`"
                        />
        
                        <label
                            :for="`${name}[delete]`"
                            class="cursor-pointer !text-sm !font-semibold !text-gray-600 dark:!text-gray-300"
                        >
                            @lang('admin::app.configuration.index.delete')
                        </label>
                    </x-admin::form.control-group>
                </template>
            </template>

            <template v-if="field.type == 'file' && field.isVisible">
                <a
                    v-if="value"
                    :href="`{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), '']) }}/${value.split('/')[1]}`"
                >
                    <div class="mb-1 inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                        <i class="icon-down-stat text-2xl"></i>
                    </div>
                </a>
        
                <x-admin::form.control-group.control
                    type="file"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::label="label"
                />
        
                <template v-if="value">
                    <div class="flex cursor-pointer gap-2.5">
                        <x-admin::form.control-group.control
                            type="checkbox"
                            ::id="`${name}[delete]`"
                            ::name="`${name}[delete]`"
                            value="1"
                        />
        
                        <label
                            class="cursor-pointer"
                            ::for="`${name}[delete]`"
                        >
                            @lang('admin::app.configuration.index.delete')
                        </label>
                    </div>
                </template>
            </template>

            <template v-if="field.type == 'country' && field.isVisible">
                <v-country :selected-country="value">
                    <template v-slot:default="{ changeCountry }">
                        <x-admin::form.control-group class="flex">
                            <x-admin::form.control-group.control
                                type="select"
                                ::id="name"
                                ::name="name"
                                ::rules="validations"
                                ::value="value"
                                ::label="label"
                                @change="changeCountry($event.target.value)"
                            >
                                <option value="">
                                    @lang('admin::app.configuration.index.select-country')
                                </option>
        
                                @foreach (core()->countries() as $country)
                                    <option value="{{ $country->code }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </template>
                </v-country>
            </template>
        
            <!-- State select Vue component -->
            <template v-if="field.type == 'state' && field.isVisible">
                <v-state>
                    <template v-slot:default="{ countryStates, country, haveStates, isStateComponenetLoaded }">
                        <div v-if="isStateComponenetLoaded">
                            <template v-if="haveStates()">
                                <x-admin::form.control-group class="flex">
                                    <x-admin::form.control-group.control
                                        type="select"
                                        ::id="name"
                                        ::name="name"
                                        ::rules="validations"
                                        ::value="value"
                                        ::label="label"
                                    >
                                        <option value="">
                                            @lang('admin::app.configuration.index.select-state')
                                        </option>
                                        
                                        <option value="*">
                                            *
                                        </option>
                                        
                                        <option
                                            v-for='(state, index) in countryStates[country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </template>
        
                            <template v-else>
                                <x-admin::form.control-group class="flex">
                                    <x-admin::form.control-group.control
                                        type="text"
                                        ::id="name"
                                        ::name="name"
                                        ::rules="validations"
                                        ::value="value"
                                        ::label="label"
                                    />
                                </x-admin::form.control-group>
                            </template>
                        </div>
                    </template>
                </v-state>
            </template>
        
            <p
                v-if="field.info && field.isVisible"
                class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300"
                v-text="info"
            >
            </p>
     
            <!-- validaiton message -->
            <v-error-message
                :name="name"
                v-slot="{ message }"
            >
                <p
                    class="mt-1 text-xs italic text-red-600"
                    v-text="message"
                >
                </p>
            </v-error-message>
        </x-admin::form.control-group>
    </script>

    <script
        type="text/x-template"
        id="v-country-template"
    >
        <div>
            <slot :changeCountry="changeCountry"></slot>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-state-template"
    >
        <div>
            <slot
                :country="country"
                :country-states="countryStates"
                :have-states="haveStates"
                :is-state-componenet-loaded="isStateComponenetLoaded"
            >
            </slot>
        </div>
    </script>

    <script type="module">
        app.component('v-configurable', {
            template: '#v-configurable-template',

            props: [
                'channelCount',
                'channelLocale',
                'currentChannel',
                'currentLocale',
                'dependName',
                'fieldData',
                'info',
                'isRequire',
                'label',
                'name',
                'src',
                'validations',
                'value',
            ],

            data() {
                return {
                    field: JSON.parse(this.fieldData),
                };
            },

            mounted() {
                if (! this.dependName) {
                    return;
                }

                const dependElement = document.getElementById(this.dependName);

                if (! dependElement) {
                    return;
                }

                dependElement.addEventListener('change', (event) => {
                    this.field['isVisible'] = 
                        event.target.type === 'checkbox' 
                        ? event.target.checked
                        : this.validations.split(',').slice(1).includes(event.target.value);
                });

                dependElement.dispatchEvent(new Event('change'));
            },
        });

        app.component('v-country', {
            template: '#v-country-template',

            props: ['selectedCountry'],

            data() {
                return {
                    country: this.selectedCountry,
                };
            },

            mounted() {
                this.$emitter.emit('country-changed', this.country);
            },

            methods: {
                changeCountry(selectedCountryCode) {
                    this.$emitter.emit('country-changed', selectedCountryCode);
                },
            },
        });

        app.component('v-state', {
            template: '#v-state-template',

            data() {
                return {
                    country: "",

                    isStateComponenetLoaded: false,

                    countryStates: @json(core()->groupedStatesByCountries())
                };
            },

            created() {
                this.$emitter.on('country-changed', (value) => this.country = value);

                setTimeout(() => {
                    this.isStateComponenetLoaded = true;
                }, 0);
            },

            methods: {
                haveStates() {
                    /*
                    * The double negation operator is used to convert the value to a boolean.
                    * It ensures that the final result is a boolean value,
                    * true if the array has a length greater than 0, and otherwise false.
                    */
                    return !!this.countryStates[this.country]?.length;
                },
            },
        });
    </script>
@endPushOnce