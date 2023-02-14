<script>
let $window = typeof window !== "undefined" ? window : null;

export function mockWindow(self) {
    $window = self || window; // mock window for unit testing
}

let $url = window.location.href;
</script>
<script setup>
import { ref, toRefs } from "vue";
import Icon from "@core/Components/Heroicon.vue";
import InlineSvg from "vue-inline-svg";


const props = defineProps({
    svgClass: {
        type: String,
        default: 'w-8 h-8'
    },
    showLabel: Boolean,
    network: {
        type: String,
        required: true
    },

    // /**
    //  * URL of the content to share.
    //  */
    // url: {
    //     type: String,
    //     required: true
    // },

    /**
     * Title of the content to share.
     */
    title: {
        type: String,
        required: true
    },

    /**
     * Description of the content to share.
     */
    description: {
        type: String,
        default: ""
    },

    /**
     * Quote content, used for Facebook.
     */
    quote: {
        type: String,
        default: ""
    },

    /**
     * Hashtags, used for Twitter and Facebook.
     */
    hashtags: {
        type: String,
        default: ""
    },

    /**
     * Twitter user, used for Twitter
     * @var string
     */
    twitterUser: {
        type: String,
        default: ""
    },

    /**
     * Media to share, used for Pinterest
     */
    media: {
        type: String,
        default: ""
    },

    /**
     * HTML tag used by the Network component.
     */
    tag: {
        type: String,
        default: "a"
    },

    /**
     * Properties to configure the popup window.
     */
    popup: {
        type: Object,
        default: () => ({
            width: 626,
            height: 436
        })
    }
});

const {
          network,
          hashtags,
          twitterUser,
          url,
          title,
          description,
          quote,
          media,
          popup
      } = toRefs(props);

const emit = defineEmits(["change", "close", "open"]);

const popupTop = ref(0);
const popupLeft = ref(0);
const popupWindow = ref(undefined);
const popupInterval = ref(null);

const networks = {
    baidu: "http://cang.baidu.com/do/add?iu=@u&it=@t",
    buffer: "https://bufferapp.com/add?text=@t&url=@u",
    email: "mailto:?subject=@t&body=@u%0D%0A@d",
    evernote: "https://www.evernote.com/clip.action?url=@u&title=@t",
    facebook: "https://www.facebook.com/sharer/sharer.php?u=@u&title=@t&description=@d&quote=@q&hashtag=@h",
    flipboard: "https://share.flipboard.com/bookmarklet/popout?v=2&url=@u&title=@t",
    hackernews: "https://news.ycombinator.com/submitlink?u=@u&t=@t",
    instapaper: "http://www.instapaper.com/edit?url=@u&title=@t&description=@d",
    line: "http://line.me/R/msg/text/?@t%0D%0A@u%0D%0A@d",
    linkedin: "https://www.linkedin.com/sharing/share-offsite/?url=@u",
    messenger: "fb-messenger://share/?link=@u",
    odnoklassniki: "https://connect.ok.ru/dk?st.cmd=WidgetSharePreview&st.shareUrl=@u&st.comments=@t",
    pinterest: "https://pinterest.com/pin/create/button/?url=@u&media=@m&description=@t",
    pocket: "https://getpocket.com/save?url=@u&title=@t",
    quora: "https://www.quora.com/share?url=@u&title=@t",
    reddit: "https://www.reddit.com/submit?url=@u&title=@t",
    skype: "https://web.skype.com/share?url=@t%0D%0A@u%0D%0A@d",
    sms: "sms:?body=@t%0D%0A@u%0D%0A@d",
    stumbleupon: "https://www.stumbleupon.com/submit?url=@u&title=@t",
    telegram: "https://t.me/share/url?url=@u&text=@t%0D%0A@d",
    tumblr: "https://www.tumblr.com/share/link?url=@u&name=@t&description=@d",
    twitter: "https://twitter.com/intent/tweet?text=@t&url=@u&hashtags=@h@tu",
    viber: "viber://forward?text=@t%0D%0A@u%0D%0A@d",
    vk: "https://vk.com/share.php?url=@u&title=@t&description=@d&image=@m&noparse=true",
    weibo: "http://service.weibo.com/share/share.php?url=@u&title=@t&pic=@m",
    whatsapp: "https://api.whatsapp.com/send?text=@t%0D%0A@u%0D%0A@d",
    wordpress: "https://wordpress.com/press-this.php?u=@u&t=@t&s=@d&i=@m",
    xing: "https://www.xing.com/social/share/spi?op=share&url=@u&title=@t",
    yammer: "https://www.yammer.com/messages/new?login=true&status=@t%0D%0A@u%0D%0A@d"
};

/**
 * Formatted network name.
 */
const key = () => {
    return network.value?.toLowerCase();
};

/**
 * Network sharing raw sharing link.
 */
const rawLink = () => {
    const ua = navigator.userAgent.toLowerCase();

    /**
     * On IOS, SMS sharing link need a special formatting
     * Source: https://weblog.west-wind.com/posts/2013/Oct/09/Prefilling-an-SMS-on-Mobile-Devices-with-the-sms-Uri-Scheme#Body-only
     */
    if (key() === "sms" && (ua.indexOf("iphone") > -1 || ua.indexOf("ipad") > -1)) {
        return networks[key()].replace(":?", ":&");
    }

    return networks[key()];
};

/**
 * Encoded hashtags for the current social network.
 */
const encodedHashtags = () => {
    if (key() === "facebook" && hashtags.value.length) {
        return "%23" + hashtags.value.split(",")[0];
    }

    return hashtags.value;
};

/**
 * Create the url for sharing.
 */
const shareLink = () => {
    let link = rawLink();

    /**
     * Twitter sharing shouldn't include empty parameter
     * Source: https://github.com/nicolasbeauvais/vue-social-sharing/issues/143
     */
    if (key() === "twitter") {
        if (!hashtags.value.length) link = link.replace("&hashtags=@h", "");
        if (!twitterUser.value.length) link = link.replace("@tu", "");
    }

    return link
    .replace(/@tu/g, "&via=" + encodeURIComponent(twitterUser.value))
    .replace(/@u/g, encodeURIComponent($url))
    .replace(/@t/g, encodeURIComponent(title.value))
    .replace(/@d/g, encodeURIComponent(description.value))
    .replace(/@q/g, encodeURIComponent(quote.value))
    .replace(/@h/g, encodedHashtags)
    .replace(/@m/g, encodeURIComponent(media.value));
};

/**
 * Center the popup on multi-screens
 * http://stackoverflow.com/questions/4068373/center-a-popup-window-on-screen/32261263
 */
const resizePopup = () => {
    const width = $window.innerWidth || (document.documentElement.clientWidth || $window.screenX);
    const height = $window.innerHeight || (document.documentElement.clientHeight || $window.screenY);
    const systemZoom = width / $window.screen.availWidth;

    popupLeft.value = (width - popup.value.width) / 2 / systemZoom + ($window.screenLeft !== undefined
                                                                      ? $window.screenLeft : $window.screenX);
    popupTop.value = (height - popup.value.height) / 2 / systemZoom + ($window.screenTop !== undefined
                                                                       ? $window.screenTop : $window.screenY);
};

/**
 * Shares URL in specified network.
 */
const share = () => {
    resizePopup();

    // If a popup window already exist, we close it and trigger a change event.
    if (popupWindow.value && popupInterval.value) {
        clearInterval(popupInterval.value);

        // Force close (for Facebook)
        popupWindow.value.close();

        emit("change");
    }

    popupWindow.value = $window.open(shareLink(), "sharer-" + key(), ",height=" + popup.value.height + ",width=" + popup.value.width + ",left=" + popupLeft.value + ",top=" + popupTop.value + ",screenX=" + popupLeft.value + ",screenY=" + popupTop.value);

    // If popup are prevented (AdBlocker, Mobile App context..), popup.window stays undefined and we can't display it
    if (!popupWindow.value) return;

    popupWindow.value.focus();

    // Create an interval to detect popup closing event
    popupInterval.value = setInterval(() => {
        if (!popupWindow.value || popupWindow.value.closed) {
            clearInterval(popupInterval.value);

            popupWindow.value = null;

            emit("close");
        }
    }, 500);

    emit("open");
};

/**
 * Touches network and emits click event.
 */
const touch = () => {
    window.open(shareLink(), "_blank");

    this.emit("open");
};

const onClick = () => {
    if(rawLink().substring(0, 4) === 'http'){
        share();
    } else {
        touch();
    }
}
</script>
<template>
    <button @click.stop="onClick()">
        <slot :onClick="onClick">
            <div class="flex items-center flex-wrap gap-1 p-2 justify-center" :class="key()">
                <InlineSvg :class="[svgClass]" class="min-w-max" :src="'/vendor/core/icons/social-networks/' + key() + '.svg'"/>
                <span v-if="showLabel" class="min-w-max px-2 py-1">{{ network }}</span>
            </div>
        </slot>
    </button>
</template>
<style scoped>
.facebook {
    @apply bg-[#2374E1] hover:bg-[#16488b] fill-white text-white
}

.twitter {
    @apply bg-[#1A8CD8] hover:bg-[#11517c] fill-white text-white
}

.whatsapp {
    @apply bg-[#2ED56B] hover:bg-[#1d8944] fill-white text-white
}

.telegram {
    @apply bg-[#20A0E1] hover:bg-[#0092db] fill-white text-white
}
</style>
