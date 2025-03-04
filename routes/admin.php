<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CronController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AddonController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Client\TeamController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\UtilityController;
use App\Http\Controllers\Admin\AiWriterController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Client\ContactController;
use App\Http\Controllers\Client\SegmentController;
use App\Http\Controllers\Client\BotReplyController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\WebsiteFaqController;
use App\Http\Controllers\Webhook\TelegramController;
use App\Http\Controllers\Webhook\WhatsAppWebhookController;
use App\Http\Controllers\Admin\Email\EmailController;
use App\Http\Controllers\Admin\WebsitePageController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\WebsiteStoryController;
use App\Http\Controllers\Client\FlowBuilderController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\WebsiteFeatureController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\WebsiteAdvantageController;
use App\Http\Controllers\Client\TelegramCampaignController;
use App\Http\Controllers\Admin\CustomNotificationController;
use App\Http\Controllers\Admin\WebsitePartnerLogoController;
use App\Http\Controllers\Admin\WebsiteTestimonialController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\WebsiteUniqueFeatureController;
use App\Http\Controllers\Admin\WebsiteSetting\FooterSettingController;
use App\Http\Controllers\Admin\WebsiteSetting\HeaderSettingController;
Route::get('client-staff', [ClientController::class, 'clientStaff'])->name('client_staff.list');
Route::group(['prefix' => localeRoutePrefix()], function () {
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified', 'adminCheck', 'PermissionCheck']], function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'verified']);
        Route::resource('cities', CityController::class)->except(['show']);
        Route::resource('languages', LanguageController::class)->except(['show', 'update']);
        Route::post('languages/update/{id}', [LanguageController::class, 'update'])->name('languages.update');
        Route::get('language/translations', [LanguageController::class, 'translationPage'])->name('language.translations.page');
        Route::resource('staffs', StaffController::class);
        Route::resource('clients', ClientController::class);
        Route::get('login-as/{id}', [AuthenticatedSessionController::class, 'login_as'])->name('login.as');
        Route::resource('pages', PageController::class)->except(['show', 'update']);
        Route::post('pages/update/{id}', [PageController::class, 'update'])->name('pages.update');
        Route::group(['as' => 'staffs.'], function () {
            Route::get('staffs/verified/{verify}', [StaffController::class, 'StaffVerified'])->name('verified');
            Route::get('staffs/bann/{id}', [StaffController::class, 'StaffBanned'])->name('bannUser');
            Route::delete('staffs/delete/{id}', [StaffController::class, 'staffDelete'])->name('delete');
        });
        /*-----============ Email setting ========================= */
        Route::group(['as' => 'email.'], function () {
            Route::get('email/server-configuration', [EmailController::class, 'serverConfiguration'])->name('server-configuration');
            Route::put('email/server-configuration', [EmailController::class, 'serverConfigurationUpdate'])->name('server-configuration.update');
            Route::post('test/email', [EmailController::class, 'sendTestMail'])->name('test');
            Route::get('email/template', [EmailController::class, 'emailTemplate'])->name('template');
            Route::put('email-template/update', [EmailController::class, 'emailTemplateUpdate'])->name('template.update');
        });
        Route::group(['prefix' => 'clients'], function () {
            Route::delete('/{id}/delete', [ClientController::class, 'delete'])->name('team.delete');
            Route::get('/{client_id}/overview', [ClientController::class, 'overview'])->name('team.overview');
        });
        Route::group(['as' => 'users.'], function () {
            Route::get('users/verified/{verify}', [UserController::class, 'instructorVerified'])->name('verified');
            Route::get('users/ban/{id}', [UserController::class, 'instructorBan'])->name('ban');
            Route::post('user-status', [UserController::class, 'statusChange'])->name('status');
            Route::delete('users/delete/{id}', [UserController::class, 'instructorDelete'])->name('delete');
        });
        //system setting
        Route::get('system-setting', [SystemSettingController::class, 'generalSetting'])->name('general.setting');
        Route::post('system-setting', [SystemSettingController::class, 'generalSettingUpdate'])->name('general.setting-update');
        //currency setting
        Route::resource('currencies', CurrencyController::class)->except(['show', 'update']);
        Route::post('currencies/update/{id}', [CurrencyController::class, 'update'])->name('currencies.update');
        Route::get('currencies/{id}/default-currency', [CurrencyController::class, 'setDefault'])->name('currencies.default-currency');
        Route::post('set-currency-format', [CurrencyController::class, 'setCurrencyFormat'])->name('set.currency.format');
        //cache
        Route::get('cache', [SystemSettingController::class, 'cache'])->name('admin.cache');
        Route::post('cache-update', [SystemSettingController::class, 'cacheUpdate'])->name('cache.update');
        //firebase setting
        Route::get('firebase', [SystemSettingController::class, 'firebase'])->name('admin.firebase');
        Route::post('firebase', [SystemSettingController::class, 'firebaseUpdate'])->name('firebase.update');
        //firebase setting
        Route::get('refund-setting', [SystemSettingController::class, 'refund'])->name('admin.refund');
        Route::post('refund-setting', [SystemSettingController::class, 'saveRefundSetting'])->name('admin.refund');
        //preferences
        Route::get('preference', [SystemSettingController::class, 'preference'])->name('preference');
        //storage setting
        Route::get('storage-setting', [SystemSettingController::class, 'storageSetting'])->name('storage.setting');
        Route::post('storage-setting', [SystemSettingController::class, 'saveStorageSetting'])->name('storage.setting');
        //chat setting
        Route::get('chat-messenger', [SystemSettingController::class, 'chatMessenger'])->name('chat.messenger');
        Route::post('chat-messenger', [SystemSettingController::class, 'saveMessengerSetting'])->name('chat.messenger');
        //payment methods
        Route::get('payment-gateway', [SystemSettingController::class, 'paymentGateways'])->name('payment.gateway');
        Route::post('payment-gateway', [SystemSettingController::class, 'savePGSetting'])->name('payment.gateway');
        /*------==== Notification ------------------======= */
        //pusher notification
        Route::get('pusher-notification', [SystemSettingController::class, 'pusher'])->name('pusher.notification');
        Route::post('pusher-notification', [SystemSettingController::class, 'savePusherSetting'])->name('pusher.notification');
        Route::get('pusher/test/event', [SystemSettingController::class, 'triggerPusherTestEvent'])->name('admin.pusher.test');
        Route::get('/check-pusher-credentials', [SystemSettingController::class, 'checkPusherCredentials'])->name('admin.pusher.check-pusher-credentials');
        //one signal notification
        Route::get('one-signal-notification', [SystemSettingController::class, 'oneSignal'])->name('onesignal.notification');
        Route::post('one-signal-notification', [SystemSettingController::class, 'saveOneSignalSetting'])->name('onesignal.notification');
        Route::get('/check-onesignal-credentials', [SystemSettingController::class, 'checkOneSignalCredentials'])->name('admin.check.onesignal.credentials');
        Route::get('/test-onesignal-notification', [SystemSettingController::class, 'testOneSignalNotification'])->name('admin.test.onesignal.notification');
        //one signal notification
        Route::get('one-signal-notification', [SystemSettingController::class, 'oneSignal'])->name('onesignal.notification');
        Route::post('one-signal-notification', [SystemSettingController::class, 'saveOneSignalSetting'])->name('onesignal.notification');
        //custom notification
        Route::resource('custom-notification', CustomNotificationController::class)->except(['show']);
        //admin panel setting
        Route::get('panel-setting', [SystemSettingController::class, 'adminPanelSetting'])->name('admin.panel-setting');
        Route::post('panel-setting', [SystemSettingController::class, 'updateSetting'])->name('admin.panel-setting.update');
        // miscellaneous setting
        Route::get('miscellaneous-setting', [SystemSettingController::class, 'miscellaneousSetting'])->name('miscellaneous.setting');
        Route::post('miscellaneous-setting-update', [SystemSettingController::class, 'miscellaneousUpdate'])->name('admin.miscellaneous.update');
        // cron setting
        Route::get('cron-setting', [SystemSettingController::class, 'cronSetting'])->name('cron.setting');
        Route::post('cron-setting-update', [SystemSettingController::class, 'cronUpdate'])->name('admin.cron.update');
        //ai-setting
        Route::get('ai-writer-setting', [SystemSettingController::class, 'aiWriterSetting'])->name('ai_writer.setting');
        Route::resource('countries', CountryController::class)->except(['create', 'show', 'update']);
        Route::post('countries/update/{id}', [CountryController::class, 'update'])->name('countries.update');
        Route::resource('cities', CityController::class)->except(['create', 'show', 'update']);
        Route::post('cities/update/{id}', [CityController::class, 'update'])->name('cities.update');
        //website setting
        //website theme option
        Route::get('website/theme-options', [WebsiteSettingController::class, 'themeOptions'])->name('admin.theme.options');
        Route::post('website/theme-options', [WebsiteSettingController::class, 'updateThemesOptions'])->name('admin.theme.options');
        //website counter
        Route::get('website/counter', [WebsiteSettingController::class, 'counter'])->name('admin.theme.counter');
        Route::post('website/counter', [WebsiteSettingController::class, 'updateCounter'])->name('admin.theme.counter.update');
        //website menu
        Route::get('website/menu', [HeaderSettingController::class, 'headerMenu'])->name('admin.menu');
        Route::post('website/menu', [HeaderSettingController::class, 'updateHeaderMenu'])->name('admin.update-menu');
        //website title subtitle
        Route::get('website/section-title-subtitle', [WebsiteSettingController::class, 'sectionTitleSubtitle'])->name('admin.section_title_subtitle');
        Route::post('website/section-title-subtitle', [WebsiteSettingController::class, 'updateSectionTitleSubtitle'])->name('admin.section_title_subtitle');
        //website hero
        Route::get('website/hero-section', [WebsiteSettingController::class, 'heroSection'])->name('admin.hero.section');
        Route::post('website/hero-section', [WebsiteSettingController::class, 'updateHeroSection'])->name('admin.hero.section');
        Route::get('website/growth', [WebsiteSettingController::class, 'growthSection'])->name('admin.growth.section');
        Route::post('website/growth', [WebsiteSettingController::class, 'updateGrowthSection'])->name('admin.growth.section');
        //website client
        Route::resource('website/partner-logo', WebsitePartnerLogoController::class);
        //website story
        Route::resource('website/story', WebsiteStoryController::class);
        //website unique feature
        Route::resource('website/unique-feature', WebsiteUniqueFeatureController::class);
        Route::post('website/unique-feature-image', [WebsiteUniqueFeatureController::class, 'imageUpdate'])->name('unique-feature.image');
        Route::get('website/service', [WebsiteSettingController::class, 'service'])->name('website.service');
        Route::put('website/service/update', [WebsiteSettingController::class, 'serviceUpdate'])->name('website.service.update');
        //website feature
        Route::resource('website/feature', WebsiteFeatureController::class);
        //website ai chat
        Route::get('website/ai-chat', [WebsiteSettingController::class, 'aiChatSection'])->name('admin.ai.chat');
        Route::post('website/ai-chat', [WebsiteSettingController::class, 'updateAiChatSection'])->name('admin.ai.chat');
        //testimonials
        Route::resource('website/testimonials', WebsiteTestimonialController::class)->except(['show']);
        //website advantage
        Route::resource('website/advantage', WebsiteAdvantageController::class);
        //website faq
        Route::resource('website/faqs', WebsiteFaqController::class)->except(['show']);
        //website cta
        Route::get('website/cta', [WebsiteSettingController::class, 'ctaSection'])->name('admin.cta');
        Route::post('website/cta', [WebsiteSettingController::class, 'updateCTASection'])->name('admin.cta');
        //website footer-content
        Route::get('website/footer-content', [FooterSettingController::class, 'footerContent'])->name('footer.content');
        Route::get('website/primary-content-setting', [FooterSettingController::class, 'primaryContentSetting'])->name('footer.primary-content');
        Route::post('website/primary-content-setting', [FooterSettingController::class, 'saveSocialLinkSetting'])->name('footer.primary-content');
        Route::get('website/useful-link-setting', [FooterSettingController::class, 'usefulLinkSetting'])->name('footer.useful-links');
        Route::get('website/quick-link-setting', [FooterSettingController::class, 'quickLinkSetting'])->name('footer.quick-links');
        Route::get('website/payment-banner-setting', [FooterSettingController::class, 'paymentbannerSetting'])->name('footer.payment-banner-settings');
        Route::get('website/copyright-setting', [FooterSettingController::class, 'copyrightSetting'])->name('footer.copyright');
        Route::post('website/update-footer-setting', [FooterSettingController::class, 'updateSetting'])->name('footer.update-setting');
        Route::post('website/update-footer-menu', [FooterSettingController::class, 'menuUpdate'])->name('footer.update-menu');
        Route::get('website/social-link', [FooterSettingController::class, 'socialLink'])->name('footer.social-link');
        //website seo
        Route::get('website-seo', [WebsiteSettingController::class, 'seo'])->name('website.seo');
        Route::post('website-seo', [WebsiteSettingController::class, 'saveSeoSetting'])->name('website.seo');
        //website seo
        Route::get('google-setup', [WebsiteSettingController::class, 'google'])->name('google.setup');
        Route::post('google-setup', [WebsiteSettingController::class, 'saveGoogleSetup'])->name('google.setup');
        //website custom js and css
        Route::get('custom-js', [WebsiteSettingController::class, 'customJs'])->name('custom.js');
        Route::get('custom-css', [WebsiteSettingController::class, 'customCss'])->name('custom.css');
        Route::post('custom-css', [WebsiteSettingController::class, 'saveCustomCssAndJs'])->name('custom.css.js');
        //website facebook pixel
        Route::get('facebook-pixel', [WebsiteSettingController::class, 'fbPixel'])->name('fb.pixel');
        Route::post('facebook-pixel', [WebsiteSettingController::class, 'saveFbPixel'])->name('fb.pixel');
        //website page
        Route::resource('website/pages', WebsitePageController::class)->except(['show', 'update']);
        Route::post('pages/update/{id}', [WebsitePageController::class, 'update'])->name('pages.update');
        //subscribers
        Route::resource('subscribers', SubscriberController::class)->only(['index', 'destroy']);
        //server information
        Route::get('server-info', [UtilityController::class, 'serverInfo'])->name('server.info');
        Route::get('system-info', [UtilityController::class, 'serverInfo'])->name('system.info');
        Route::get('extension-library', [UtilityController::class, 'serverInfo'])->name('extension.library');
        Route::get('file-system-permission', [UtilityController::class, 'serverInfo'])->name('file.system.permission');
        //system update
        Route::get('system-update', [UtilityController::class, 'systemUpdate'])->name('system.update');
        Route::post('system-update', [UtilityController::class, 'downloadUpdate'])->name('system.update');
        //Support System
        Route::resource('tickets', TicketController::class)->except(['edit', 'destroy']);
        Route::get('ticket/update/{id}', [TicketController::class, 'update'])->name('ticket.update');
        Route::post('ticket-reply', [TicketController::class, 'reply'])->name('ticket.reply');
        Route::get('ticket-reply-edit/{id}', [TicketController::class, 'replyEdit'])->name('ticket.reply.edit');
        Route::post('ticket-reply-update/{id}', [TicketController::class, 'replyUpdate'])->name('ticket.reply.update');
        Route::delete('ticket-reply-delete/{id}', [TicketController::class, 'replyDelete'])->name('ticket.reply.delete');
        Route::resource('departments', DepartmentController::class)->except(['show']);
        //packages
        Route::resource('plans', PlanController::class)->except(['show']);
        Route::post('packages/{subscribe}', [PlanController::class, 'PackageSubscribe'])->name('packages.subscribe');
        Route::get('subscriptions', [SubscriptionController::class, 'PackageSubscribeList'])->name('packages.subscribe-list');
        Route::delete('subscription/status/{id}', [SubscriptionController::class, 'subscribeListStatus'])->name('subscribe-list.status');
        Route::delete('stop-recurring/{id}', [SubscriptionController::class, 'stopRecurring'])->name('stop.recurring');
        Route::delete('cancel-subscription/{id}', [SubscriptionController::class, 'cancelSubscription'])->name('cancel.subscription');
        Route::post('add-validity/{id}', [SubscriptionController::class, 'addValidity'])->name('add.validity');
        Route::post('add-credit', [SubscriptionController::class, 'addCredit'])->name('add.credit');
        Route::post('create-subscription', [SubscriptionController::class, 'addSubscription'])->name('create.subscription');
        //admin profile
        Route::get('profile', [AdminController::class, 'profile'])->name('user.profile');
        Route::post('onesignal/update-subscription', [AdminController::class, 'oneSignalSubscribe'])->name('onesignal.update-subscription');
        Route::patch('user-update', [AdminController::class, 'profileUpdate'])->name('user.update');
        Route::get('password-change', [AdminController::class, 'passwordChange'])->name('user.password-change');
        Route::post('password-update', [AdminController::class, 'passwordUpdate'])->name('user.password-update');
        //addons
        Route::resource('addon', AddonController::class)->only(['index', 'store']);
        //AI writer
        Route::get('ai-writer', [AiWriterController::class, 'index'])->name('ai.writer');
        Route::post('ai-writer', [AiWriterController::class, 'saveAiSetting'])->name('ai.writer');
        Route::get('ai-writer-setting', [SystemSettingController::class, 'aiWriterSetting'])->name('ai_writer.setting');
        Route::post('generated-ai-content', [AiWriterController::class, 'generateContent'])->name('ai.content');
        Route::post('message-limit', [SystemSettingController::class, 'updateMessageSetting'])->name('admin.message-limit.update');
    });

    //user route
    Route::get('users/ban/{id}', [UserController::class, 'userBan'])->name('users.ban');
    /*------==== Email template route ------------------======= */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified'], 'as' => 'email.'], function () {
        Route::post('template-body', [EmailController::class, 'templateBody'])->name('template-body');
    });
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified'], 'as' => 'languages.'], function () {
        Route::post('language-status', [LanguageController::class, 'statusChange'])->name('language-status');
        Route::post('language-direction-change', [LanguageController::class, 'directionChange'])->name('language-direction-change');
    }); 
    Route::post('language/store/key/{id}',[LanguageController::class, 'storeLanguageKeyword'])->name('admin.language.store.key');
    Route::post('language/delete/key/{id}', [LanguageController::class, 'removeLanguageKey'])->name('admin.language.delete.key');
    Route::get('language/edit/key/{id}', [LanguageController::class, 'editLanguageKey'])->name('admin.language.edit.key');
    Route::post('language/update/key/{id}', [LanguageController::class, 'updateLanguageKey'])->name('admin.language.update.key');
    Route::post('language/search/key', [LanguageController::class, 'keywordSearchAndReplace'])->name('admin.language.replace.key');
    Route::get('admin/language/scan/{id}', [LanguageController::class, 'scanAndStore'])->name('admin.language.scan');
    Route::get('admin/translations/missing-keys/{id}', [LanguageController::class, 'findMissingKeys'])->name('admin.language.missing-keys');
    Route::post('admin/languages/{language}', [LanguageController::class, 'updateTranslations'])->name('admin.language.key.update');
    /* ajax request route without route permission for status */
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'verified']], function () {
        Route::post('onesignal-subscription', [AdminController::class, 'oneSignalSubscription'])->name('admin.onesignal');
        Route::post('staffs-status', [StaffController::class, 'statusChange'])->name('staffs.status');
        Route::post('botReplay-status', [BotReplyController::class, 'statusChange'])->name('botReplay.status');
        Route::post('team-status', [TeamController::class, 'statusChange'])->name('team.status');
        Route::post('flow-builder-status', [FlowBuilderController::class, 'statusChange'])->name('flow-builder.status');
        Route::post('contact-status', [ContactController::class, 'statusChange'])->name('contact.status');
        Route::get('change-role', [StaffController::class, 'changeRole'])->name('staffs.change-role');
        Route::post('clients-status', [ClientController::class, 'statusChange'])->name('clients.organizations-status');
        Route::post('currencies-status', [CurrencyController::class, 'statusChange'])->name('currencies.currencies-status');
        Route::post('countries-status', [CountryController::class, 'statusChange'])->name('countries.countries-status');
        Route::post('cities-status', [CityController::class, 'statusChange'])->name('cities.countries-status');
        Route::post('setting-status-change', [SystemSettingController::class, 'systemStatus'])->name('setting.status.change');
        Route::post('pages-status', [PageController::class, 'statusChange'])->name('page.status.change');
        Route::post('pages-status', [WebsitePageController::class, 'statusChange'])->name('page.status.change');
        Route::post('addon-status', [AddonController::class, 'statusChange'])->name('addon.status.change');
        Route::post('department-status', [DepartmentController::class, 'statusChange'])->name('department.status.change');
        Route::post('testimonial-status', [WebsiteTestimonialController::class, 'statusChange'])->name('testimonial.status.change');
        Route::post('package-status', [PlanController::class, 'statusChange'])->name('package.status.change');
        Route::post('segments-status', [SegmentController::class, 'statusChange'])->name('segments.status');
        Route::post('partner-logo-status', [WebsitePartnerLogoController::class, 'statusChange'])->name('partner-logo-status.change');
        Route::post('website-feature-status', [WebsiteFeatureController::class, 'statusChange'])->name('website.feature-status.change');
        Route::post('website-advantage-status', [WebsiteAdvantageController::class, 'statusChange'])->name('website-advantage-status');
        Route::post('website-unique-feature-status', [WebsiteUniqueFeatureController::class, 'statusChange'])->name('website.unique-feature-status.change');
        Route::post('website-story-status', [WebsiteStoryController::class, 'statusChange'])->name('website.story-status.change');
        Route::post('faq-status', [WebsiteFaqController::class, 'statusChange'])->name('faqs.status.change');
        Route::post('telegram-groups-status', [TelegramCampaignController::class, 'statusChange'])->name('telegram.groups.status.change');
    });
    //for ajax request
    Route::prefix('ajax')->as('ajax.')->middleware(['auth', 'verified'])->group(function () {
        Route::get('users', [AjaxController::class, 'user'])->name('users');
        Route::get('organizations', [AjaxController::class, 'organizations'])->name('organizations');
        Route::get('states-by-country', [AjaxController::class, 'getStates'])->name('states');
        Route::get('cities-by-state', [AjaxController::class, 'getCities'])->name('cities');
    });
});
