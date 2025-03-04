<?php
use App\Http\Controllers\Api\Client\TicketController;
use App\Http\Controllers\Api\Client\TeamController;
use App\Http\Controllers\Api\Client\Whatsapp\ContactController;
use App\Http\Controllers\Api\Client\Whatsapp\ContactListController;
use App\Http\Controllers\Api\Client\Whatsapp\SegmentController;
use App\Http\Controllers\Api\Client\Whatsapp\CampaignController as WhatsappCampaignController;
use App\Http\Controllers\Api\Client\Whatsapp\BotRepliesController;
use App\Http\Controllers\Api\Client\Whatsapp\TemplateController;
use App\Http\Controllers\Api\Client\Telegram\ContactController as TelegramContactController;
use App\Http\Controllers\Api\Client\Telegram\CampaignController as TelegramCampaignController;
use App\Http\Controllers\Api\Client\Telegram\GroupController;
use App\Http\Controllers\Api\Client\AuthController;
use Illuminate\Support\Facades\Route;
Route::group(['prefix' => localeRoutePrefix().'/api',], function () {
    Route::middleware(['CheckApiKey'])->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::middleware('jwt.verify')->group(function () {
            Route::post('logout', [AuthController::class, 'logout']);
            //------------Whatsapp start----------//
            //Contact api
            Route::get('whatsapp-contact', [ContactController::class, 'allContact']);
            Route::post('whatsapp-contact-store', [ContactController::class, 'submitContact']);
            Route::post('whatsapp-contact-update/{id}', [ContactController::class, 'submitContact']);
            //Contact-list api
            Route::get('whatsapp-contact-list', [ContactListController::class, 'allContactList']);
            Route::post('whatsapp-contact-list-store', [ContactListController::class, 'submitContactList']);
            Route::post('whatsapp-contact-list-update/{id}', [ContactListController::class, 'submitContactList']);
            //Segment api
            Route::get('whatsapp-segment', [SegmentController::class, 'allSegment']);
            Route::post('whatsapp-segment-store', [SegmentController::class, 'submitSegment']);
            Route::post('whatsapp-segment-update/{id}', [SegmentController::class, 'submitSegment']);
            //Bot Replies api
            Route::get('whatsapp-bot-replies', [BotRepliesController::class, 'allBotReplies']);
            Route::post('whatsapp-bot-replies-store', [BotRepliesController::class, 'submitBotReplies']);
            Route::post('whatsapp-bot-replies-update/{id}', [BotRepliesController::class, 'submitBotReplies']);
            //Campaign api
            Route::get('whatsapp-campaign', [WhatsappCampaignController::class, 'allCampaign']);
            Route::post('whatsapp-campaign-store', [WhatsappCampaignController::class, 'submitCampaign']);
            Route::post('whatsapp-campaign-update/{id}', [WhatsappCampaignController::class, 'submitCampaign']);
            //Template api
            Route::get('whatsapp-template', [TemplateController::class, 'allTemplate']);
            //------------Whatsapp end----------//
            //------------Telegram start----------//
            //Group api
            Route::get('telegram-group', [GroupController::class, 'allGroup']);
            //Contact api
            Route::get('telegram-contact', [TelegramContactController::class, 'allContact']);
            //Campaign api
            Route::get('telegram-campaign', [TelegramCampaignController::class, 'allCampaign']);
            Route::post('telegram-campaign-store', [TelegramCampaignController::class, 'submitCampaign']);
            Route::post('telegram-campaign-update/{id}', [TelegramCampaignController::class, 'submitCampaign']);
            //------------Telegram end----------//
            //Ticket api
            Route::get('ticket', [TicketController::class, 'allTicket']);
            Route::post('ticket-store', [TicketController::class, 'createTicket']);
            Route::post('ticket-reply', [TicketController::class, 'replyTicket']);
            Route::get('ticket-reply-edit/{id}', [TicketController::class, 'replyEdit']);
            Route::post('ticket-reply-update/{id}', [TicketController::class, 'replyUpdateTicket']);
            //Team api
            Route::get('team', [TeamController::class, 'allTeam']);
            Route::post('team-store', [TeamController::class, 'submitTeam']);
            Route::post('team-update/{id}', [TeamController::class, 'submitTeam']);

        });
    });
});
