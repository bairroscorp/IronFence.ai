<script setup lang="ts">
import { dashboard, login, register } from '@/routes';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();

function toggleLocale() {
  locale.value = locale.value === 'pt' ? 'en' : 'pt';
}
</script>

<template>
    
    <Head :title="t('welcome.title')">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]">
        <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-between gap-4">
                <!-- Botão de idioma -->
                <button
                  @click="toggleLocale"
                  class="px-3 py-1 text-xs rounded bg-gray-700 text-white hover:bg-gray-600"
                >
                  {{ locale === 'pt' ? 'EN' : 'PT' }}
                </button>

                <div class="flex gap-4">
                  <Link
                      v-if="$page.props.auth.user"
                      :href="dashboard()"
                      class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                  >
                      {{ t('welcome.dashboard') }}
                  </Link>
                  <template v-else>
                      <Link
                          :href="login()"
                          class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                      >
                          {{ t('welcome.login') }}
                      </Link>
                      <Link
                          :href="register()"
                          class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                      >
                          {{ t('welcome.register') }}
                      </Link>
                  </template>
                </div>
            </nav>
        </header>

        <div class="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
            <!-- conteúdo extra aqui -->
        </div>

        <div class="hidden h-14.5 lg:block"></div>
    </div>
</template>