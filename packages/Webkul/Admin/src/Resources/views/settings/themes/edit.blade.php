<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.edit.title')
    </x-slot>

    @php
        $channels = core()->getAllChannels();

        $currentChannel = core()->getRequestedChannel();

        $currentLocale = core()->getRequestedLocale();
    @endphp

    <x-admin::form
        :action="route('admin.settings.themes.update', $theme->id)"
        enctype="multipart/form-data"
        v-slot="{ errors }"
    >
        <div class="flex items-center justify-between">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.settings.themes.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <div class="flex items-center gap-x-2.5">
                    <a
                        href="{{ route('admin.settings.themes.index') }}"
                        class="transparent-button hover:bg-gray-200 dark:text-white dark:hover:bg-gray-800"
                    >
                        @lang('admin::app.settings.themes.edit.back')
                    </a>
                </div>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.settings.themes.edit.save-btn')
                </button>
            </div>
        </div>

        <!-- Channel and Locale Switcher -->
        <div class="mt-7 flex items-center justify-between gap-4 max-md:flex-wrap">
            <div class="flex items-center gap-x-1">
                <!-- Locale Switcher -->
                <x-admin::dropdown :class="$currentChannel->locales->count() <= 1 ? 'hidden' : ''">
                    <!-- Dropdown Toggler -->
                    <x-slot:toggle>
                        <button
                            type="button"
                            class="transparent-button px-1 py-1.5 hover:bg-gray-200 focus:bg-gray-200 dark:text-white dark:hover:bg-gray-800 dark:focus:bg-gray-800"
                        >
                            <span class="icon-language text-2xl"></span>

                            {{ $currentLocale->name }}

                            <input
                                type="hidden"
                                name="locale"
                                value="{{ $currentLocale->code }}"
                            />

                            <span class="icon-sort-down text-2xl"></span>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="!p-0">
                        @foreach ($currentChannel->locales->sortBy('name') as $locale)
                            <a
                                href="?{{ Arr::query(['channel' => $currentChannel->code, 'locale' => $locale->code]) }}"
                                class="flex gap-2.5 px-5 py-2 text-base  cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-950 dark:text-white {{ $locale->code == $currentLocale->code ? 'bg-gray-100 dark:bg-gray-950' : ''}}"
                            >
                                {{ $locale->name }}
                            </a>
                        @endforeach
                    </x-slot>
                </x-admin::dropdown>
            </div>
        </div>

        <v-theme-customizer :errors="errors">
            <!-- Shimmer Effects -->
            @switch($theme->type)
                @case('image_carousel')
                    <x-admin::shimmer.settings.themes.image-carousel />

                    @break
                @case('product_carousel')
                    <x-admin::shimmer.settings.themes.product-carousel />

                    @break
                @case('category_carousel')
                    <x-admin::shimmer.settings.themes.category-carousel />

                    @break
                @case('static_content')
                    <x-admin::shimmer.settings.themes.static-content />

                    @break
                @case('footer_links')
                    <x-admin::shimmer.settings.themes.footer-links />

                    @break
                @case('services_content')
                    <x-admin::shimmer.settings.themes.services-content />

                    @break
                @default
                    <x-admin::shimmer.settings.themes.image-carousel />
                @endswitch
        </v-theme-customizer>
    </x-admin::form>

    @pushOnce('scripts')
        <!-- Customizer Parent Template-->
        <script
            type="text/x-template"
            id="v-theme-customizer-template"
        >
            <div>
                <component
                    :errors="errors"
                    :is="componentName"
                    ref="dynamicComponentThemeRef"
                >
                </component>
            </div>
        </script>

        <!-- Image-Carousel Template -->
        @includeWhen($theme->type === 'image_carousel', 'admin::settings.themes.edit.image-carousel')

        <!-- Product-Carousel Template -->
        @includeWhen($theme->type === 'product_carousel', 'admin::settings.themes.edit.product-carousel')

        <!-- Category Template -->
        @includeWhen($theme->type==='category_carousel', 'admin::settings.themes.edit.category-carousel')

        <!-- Static-Content Template -->
        @includeWhen($theme->type === 'static_content', 'admin::settings.themes.edit.static-content')

        <!-- Footer Template -->
        @includeWhen($theme->type === 'footer_links', 'admin::settings.themes.edit.footer-links')

        <!-- Services-content Template -->
        @includeWhen($theme->type === 'services_content', 'admin::settings.themes.edit.services-content')


        <!-- Parent Theme Customizer Component -->
        <script type="module">
            app.component('v-theme-customizer', {
                template: '#v-theme-customizer-template',

                props: ['errors'],

                data() {
                    return {
                        componentName: 'v-image-carousel',

                        themeType: {
                            product_carousel: 'v-product-carousel',
                            category_carousel: 'v-category-carousel',
                            static_content: 'v-static-content',
                            image_carousel: 'v-image-carousel',
                            footer_links: 'v-footer-links',
                            services_content: 'v-services-content'
                        }
                    };
                },

                created(){
                    this.componentName = this.themeType["{{ $theme->type }}"];
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
