<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.profile.edit.edit-profile')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-shop::breadcrumbs name="profile.edit" />
    @endSection

    <h2 class="mb-8 text-2xl font-medium">
        @lang('shop::app.customers.account.profile.edit.edit-profile')
    </h2>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

    <!-- Profile Edit Form -->
    <x-shop::form
        :action="route('shop.customers.account.profile.update')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

        <x-shop::form.control-group class="mt-4">
            <x-shop::form.control-group.control
                type="image"
                class="mb-0 rounded-xl !p-0 text-gray-700"
                name="image[]"
                :label="trans('Image')"
                :is-multiple="false"
                accepted-types="image/*"
                :src="$customer->image_url"
            />

            <x-shop::form.control-group.error control-name="image[]" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.image.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.edit.first-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="first_name"
                rules="required"
                :value="old('first_name') ?? $customer->first_name"
                :label="trans('shop::app.customers.account.profile.edit.first-name')"
                :placeholder="trans('shop::app.customers.account.profile.edit.first-name')"
            />

            <x-shop::form.control-group.error control-name="first_name" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.first_name.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.edit.last-name')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="last_name"
                rules="required"
                :value="old('last_name') ?? $customer->last_name"
                :label="trans('shop::app.customers.account.profile.edit.last-name')"
                :placeholder="trans('shop::app.customers.account.profile.edit.last-name')"
            />

            <x-shop::form.control-group.error control-name="last_name" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.last_name.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.edit.email')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="email"
                rules="required|email"
                :value="old('email') ?? $customer->email"
                :label="trans('shop::app.customers.account.profile.edit.email')"
                :placeholder="trans('shop::app.customers.account.profile.edit.email')"
            />

            <x-shop::form.control-group.error control-name="email" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.email.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.edit.phone')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="text"
                name="phone"
                rules="required|phone"
                :value="old('phone') ?? $customer->phone"
                :label="trans('shop::app.customers.account.profile.edit.phone')"
                :placeholder="trans('shop::app.customers.account.profile.edit.phone')"
            />

            <x-shop::form.control-group.error control-name="phone" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.phone.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label class="required">
                @lang('shop::app.customers.account.profile.edit.gender')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="select"
                class="mb-3"
                name="gender"
                rules="required"
                :value="old('gender') ?? $customer->gender"
                :aria-label="trans('shop::app.customers.account.profile.edit.select-gender')"
                :label="trans('shop::app.customers.account.profile.edit.gender')"
            >
                <option value="Other">
                    @lang('shop::app.customers.account.profile.edit.other')
                </option>

                <option value="Male">
                    @lang('shop::app.customers.account.profile.edit.male')
                </option>

                <option value="Female">
                    @lang('shop::app.customers.account.profile.edit.female')
                </option>
            </x-shop::form.control-group.control>

            <x-shop::form.control-group.error control-name="gender" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.gender.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.edit.dob')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="date"
                name="date_of_birth"
                :value="old('date_of_birth') ?? $customer->date_of_birth"
                :label="trans('shop::app.customers.account.profile.edit.dob')"
                :placeholder="trans('shop::app.customers.account.profile.edit.dob')"
            />

            <x-shop::form.control-group.error control-name="date_of_birth" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.date_of_birth.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.edit.current-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="current_password"
                value=""
                :label="trans('shop::app.customers.account.profile.edit.current-password')"
                :placeholder="trans('shop::app.customers.account.profile.edit.current-password')"
            />

            <x-shop::form.control-group.error control-name="current_password" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.old_password.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.edit.new-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password"
                value=""
                :label="trans('shop::app.customers.account.profile.edit.new-password')"
                :placeholder="trans('shop::app.customers.account.profile.edit.new-password')"
            />

            <x-shop::form.control-group.error control-name="new_password" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password.after') !!}

        <x-shop::form.control-group>
            <x-shop::form.control-group.label>
                @lang('shop::app.customers.account.profile.edit.confirm-password')
            </x-shop::form.control-group.label>

            <x-shop::form.control-group.control
                type="password"
                name="new_password_confirmation"
                rules="confirmed:@new_password"
                value=""
                :label="trans('shop::app.customers.account.profile.edit.confirm-password')"
                :placeholder="trans('shop::app.customers.account.profile.edit.confirm-password')"
            />

            <x-shop::form.control-group.error control-name="new_password_confirmation" />
        </x-shop::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password_confirmation.after') !!}

        <div class="mb-4 flex select-none items-center gap-1.5">
            <input
                type="checkbox"
                name="subscribed_to_news_letter"
                id="is-subscribed"
                class="peer hidden"
                @checked($customer->subscribed_to_news_letter)
            />

            <label
                class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue"
                for="is-subscribed"
            ></label>

            <label
                class="cursor-pointer select-none text-base text-zinc-500 max-sm:text-xs ltr:pl-0 rtl:pr-0"
                for="is-subscribed"
            >
                @lang('shop::app.customers.account.profile.edit.subscribe-to-newsletter')
            </label>
        </div>

        <button
            type="submit"
            class="primary-button m-0 block w-max rounded-2xl px-11 py-3 text-center text-base"
        >
            @lang('shop::app.customers.account.profile.edit.save')
        </button>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

    </x-shop::form>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}
    
</x-shop::layouts.account>
