<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use JeroenNoten\LaravelAdminLte\Events\DarkModeWasToggled;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use JeroenNoten\LaravelAdminLte\Events\ReadingDarkModePreference;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // Register listener for ReadingDarkModePreference event. We use this
        // event to setup dark mode initial status for AdminLTE package.

        Event::listen(
            ReadingDarkModePreference::class,
            [$this, 'handleReadingDarkModeEvt']
        );

        // Register listener for DarkModeWasToggled AdminLTE event.

        Event::listen(
            DarkModeWasToggled::class,
            [$this, 'handleDarkModeWasToggledEvt']
        );
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            // Add some items to the menu...
            $menus =  User::select('system_menu_mapping.*','system_menu.*')
            ->join('system_user_group','system_user_group.user_group_id','=','system_user.user_group_id')
            ->join('system_menu_mapping','system_menu_mapping.user_group_level','=','system_user_group.user_group_level')
            ->join('system_menu','system_menu.id_menu','=','system_menu_mapping.id_menu')
            ->where('system_user.user_id','=',Auth::id())->orderBy('system_menu_mapping.id_menu','ASC')->get();
            $last_key   = 'tes';
            $last_key2  = 'tes';
            $last_key3  = 'tes';
            foreach($menus as $key => $val){
                if($val['indent_level']==1||$val['menu_level']==1){
                    $event->menu->add([
                        'key'       => $val['id_menu'],
                        'text'      => $val['text'],
                        'url'       => $val['id'],
                        'active'    => [$val['id'].'/*'],
                        'icon'      => '',
                    ]);
                    $last_key = $val['id_menu'];
                }else if($val['indent_level']==2||$val['menu_level']==2){
                    $event->menu->addIn($last_key,[
                        'key'       => $val['id_menu'],
                        'text'      => $val['text'],
                        'url'       => $val['id'],
                        'active'    => [$val['id'].'/*'],
                        'classes'   => 'level-two',
                        'icon'      => '',
                    ]);
                    $last_key2 = $val['id_menu'];
                }else if($val['indent_level']==3||$val['menu_level']==3){
                    $event->menu->addIn($last_key2,[
                        'key'       => $val['id_menu'],
                        'text'      => $val['text'],
                        'url'       => $val['id'],
                        'active'    => [$val['id'].'/*'],
                        'classes'   => 'level-three',
                        'icon'      => '',
                    ]);
                    $last_key3 = $val['id_menu'];
                }else if($val['indent_level']==4||$val['menu_level']==4){
                    $event->menu->addIn($last_key3,[
                        'key'       => $val['id_menu'],
                        'text'      => $val['text'],
                        'url'       => $val['id'],
                        'active'    => [$val['id'].'/*'],
                        'classes'   => 'level-four',
                        'icon'      => '',
                    ]);
                }
            }
        });
    }
    public function handleReadingDarkModeEvt(ReadingDarkModePreference $event)
    {
        // TODO: Implement the next method to get the dark mode preference for the
        // current authenticated user. Usually this preference will be stored on a database,
        // it is your task to get it.

        // $darkModeCfg = $this->getDarkModeSettingFromDB();

        // Setup initial dark mode preference.
        $darkModeCfg = 0;
        if ($darkModeCfg) {
            $event->darkMode->enable();
        } else {
            $event->darkMode->disable();
        }
    }

    /**
     * Handle the DarkModeWasToggled AdminLTE event.
     *
     * @param DarkModeWasToggled $event
     * @return void
     */
    public function handleDarkModeWasToggledEvt(DarkModeWasToggled $event)
    {
        // Get the new dark mode preference (enabled or not).

        $darkModeCfg = $event->darkMode->isEnabled();

        if ($darkModeCfg) {
            Log::debug("Dark mode preference is now enabled!");
        } else {
            Log::debug("Dark mode preference is now disabled!");
        }

        // Store the new dark mode preference on the database.

        // $this->storeDarkModeSettingOnDB($darkModeCfg);

        // TODO: Implement previous method to store the new dark mode
        // preference for the authenticated user. Usually this preference will
        // be stored on a database, it is your task to store it.
    }
    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
