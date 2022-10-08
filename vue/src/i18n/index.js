import en from './en.json'
import ru from './ru.json'
import ua from './ua.json'
import { createI18n } from 'vue-i18n';

export const defaultLocale = 'en';

export const languages = {
    en,
    ru,
    ua
}
const messages = Object.assign(languages)

export const i18n = createI18n({
    legacy: false,
    locale: defaultLocale,
    fallbackLocale: 'en',
    messages
})
