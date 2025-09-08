<script setup lang="ts">
import AuthenticatedSessionController from "@/actions/App/Http/Controllers/Auth/AuthenticatedSessionController";
import InputError from "@/components/InputError.vue";
import TextLink from "@/components/TextLink.vue";
import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import AuthBase from "@/layouts/AuthLayout.vue";
import { register } from "@/routes";
import { request } from "@/routes/password";
import { Form, Head } from "@inertiajs/vue3";
import { LoaderCircle } from "lucide-vue-next";
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

function toggleLocale() {
  locale.value = locale.value === "pt" ? "en" : "pt";
}

defineProps<{
  status?: string;
  canResetPassword: boolean;
}>();
</script>

<template>
  <AuthBase :title="t('login.title')" :description="t('login.description')">
    <Head :title="t('login.title')" />

    <div v-if="status" class="mb-4 text-center text-sm font-medium text-green-600">
      {{ status }}
    </div>

    <!-- Botão de troca de idioma -->
    <div class="flex justify-end mb-4">
      <button
        @click="toggleLocale"
        class="px-3 py-1 text-xs rounded bg-gray-700 text-white hover:bg-gray-600"
      >
        {{ locale === "pt" ? "EN" : "PT" }}
      </button>
    </div>

    <Form
      v-bind="AuthenticatedSessionController.store.form()"
      :reset-on-success="['password']"
      v-slot="{ errors, processing }"
      class="flex flex-col gap-6"
    >
      <div class="grid gap-6">
        <div class="grid gap-2">
          <Label for="email">{{ t("login.email") }}</Label>
          <Input
            id="email"
            type="email"
            name="email"
            required
            autofocus
            :tabindex="1"
            autocomplete="email"
            :placeholder="t('login.email_placeholder')"
          />
          <InputError :message="errors.email" />
        </div>

        <div class="grid gap-2">
          <Input
            id="password"
            type="password"
            name="password"
            required
            :tabindex="2"
            autocomplete="current-password"
            :placeholder="t('login.password_placeholder')"
          />
          <InputError :message="errors.password" />
        </div>

        <div class="flex items-center justify-between">
          <Label for="remember" class="flex items-center space-x-3">
            <Checkbox id="remember" name="remember" :tabindex="3" />
            <span>{{ t("login.remember") }}</span>
          </Label>
        </div>

        <Button type="submit" class="mt-4 w-full" :tabindex="4" :disabled="processing">
          <LoaderCircle v-if="processing" class="h-4 w-4 animate-spin" />
          {{ t("login.button") }}
        </Button>
      </div>

      <div class="text-center text-sm text-muted-foreground">
        {{ t("login.signup_text") }}
        <TextLink :href="register()" :tabindex="5">{{ t("login.signup_link") }}</TextLink>
      </div>
    </Form>
  </AuthBase>
</template>
