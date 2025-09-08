<script setup lang="ts">
import RegisteredUserController from '@/actions/App/Http/Controllers/Auth/RegisteredUserController';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { Form, Head } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

function toggleLocale() {
  locale.value = locale.value === "pt" ? "en" : "pt";
}
</script>

<template>
    <AuthBase :title="t('register.title')" :description="t('register.description')">
        <Head :title="t('register.title')" />

        <!-- Botão de troca de idioma -->
        <div class="flex justify-end mb-4">
            <button
                @click="toggleLocale"
                class="px-3 py-1 text-xs rounded bg-gray-700 text-white hover:bg-gray-600"
            >
                {{ locale === 'pt' ? 'EN' : 'PT' }}
            </button>
        </div>

        <Form
            v-bind="RegisteredUserController.store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">{{ t('register.name') }}</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        :placeholder="t('register.name_placeholder')"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">{{ t('register.email') }}</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        :placeholder="t('register.email_placeholder')"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ t('register.password') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        :placeholder="t('register.password_placeholder')"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ t('register.confirm_password') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        :placeholder="t('register.confirm_password_placeholder')"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="processing">
                    <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
                    {{ t('register.button') }}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{ t('register.already_text') }}
                <TextLink :href="login()" class="underline underline-offset-4" :tabindex="6">
                    {{ t('register.login_link') }}
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
