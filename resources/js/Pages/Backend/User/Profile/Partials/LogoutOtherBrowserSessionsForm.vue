<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Flap from "@core/Components/Flap.vue";
import Input from "@core/Components/Form/Input.vue";

defineProps({
    sessions: Array
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: ""
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route("other-browser-sessions.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeFlap(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset()
    });
};

const closeFlap = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>
<template>
    <div class="p-6 ">
        <div class="max-w-xl text-sm">
            {{ __("browser_sessions_legend") }}
        </div>
        <!-- Other Browser Sessions -->
        <div v-if="sessions.length > 0"
             class="mt-5 space-y-6">
            <div v-for="(session, i) in sessions"
                 :key="i"
                 class="flex items-center">
                <div>
                    <svg v-if="session.agent.is_desktop"
                         class="w-8 h-8 text-gray-500"
                         fill="none"
                         stroke="currentColor"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2"
                         viewBox="0 0 24 24">
                        <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <svg v-else
                         class="w-8 h-8 text-gray-500"
                         fill="none"
                         stroke="currentColor"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         stroke-width="2"
                         viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="M0 0h24v24H0z"
                              stroke="none"/>
                        <rect height="16"
                              rx="1"
                              width="10"
                              x="7"
                              y="4"/>
                        <path d="M11 5h2M12 17v.01"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-sm">
                        {{ session.agent.platform ? session.agent.platform : "Unknown" }} - {{
                            session.agent.browser ? session.agent.browser : "Unknown"
                        }}
                    </div>
                    <div>
                        <div class="text-xs text-gray-500">
                            {{ session.ip_address }},
                            <span v-if="session.is_current_device"
                                  class="text-green-500 font-semibold">{{ __("this_device") }}
                            </span>
                            <span v-else>{{ __("last_active") }} {{ session.last_active }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center mt-5">
            <PrimaryButton @click="confirmLogout">
                {{ __("logout_other_browser_sessions") }}
            </PrimaryButton>
            <ActionMessage :on="form.recentlySuccessful"
                           class="ml-3">
                {{ __("done") }}.
            </ActionMessage>
        </div>
        <Flap v-model="confirmingLogout"
              :title="__('logout_other_browser_sessions')"
              close-background
              md>
            <p>
                {{ __("enter_password_confirm_logout_other_browser_sessions") }}
            </p>
            <div class="mt-4">
                <Input ref="passwordInput"
                       v-model="form.password"
                       :errors="form.errors"
                       :placeholder="__('password')"
                       name="password"
                       type="password"
                       @keyup.enter="logoutOtherBrowserSessions"/>
            </div>
            <div class="mt-4 flex gap-2">
                <button class="btn btn-secondary"
                        @click="closeFlap">
                    {{ __("cancel") }}
                </button>
                <button :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        class="btn btn-danger"
                        @click="logoutOtherBrowserSessions">
                    {{ __("logout_other_browser_sessions") }}
                </button>
            </div>
        </Flap>
    </div>
</template>
