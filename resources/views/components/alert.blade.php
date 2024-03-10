@props([
    // error, warning, success, info
    'type' => 'info',
    // shades of the alert faint, dark
    'shade' => 'faint',
    // should the alert type icon be shown
    'show_icon' => true,
    // for backward compatibility with laravel 8
    'showIcon'  => true,
    // should the close icon be shown
    'show_close_icon' => true,
    // for backward compatibility with laravel 8
    'showCloseIcon' => true,
    // additional css classes to add
    'class' => '',
    // additional colors to display
    'color' => null,
    // any Heroicons icon to use
    'icon' => '',
    // additional css to apply to $icon
    'icon_avatar_css' => '',
    // use avatar in place of an icon
    'avatar' => '',
    // size of the avatar
    'size' => 'tiny',
    'show_ring' => false,
])

@php
    // reset variables for Laravel 8 support
    $show_icon = filter_var($show_icon, FILTER_VALIDATE_BOOLEAN);
    $showIcon = filter_var($showIcon, FILTER_VALIDATE_BOOLEAN);
    $show_close_icon = filter_var($show_close_icon, FILTER_VALIDATE_BOOLEAN);
    $showCloseIcon = filter_var($showCloseIcon, FILTER_VALIDATE_BOOLEAN);
    if(!$showIcon) $show_icon = $showIcon;
    if(!$showCloseIcon) $show_close_icon = $showCloseIcon;
    $close_icon_css =  ($shade == 'dark') ? (($color =='transparent') ? 'text-gray-400 hover:text-gray-700' : 'text-white hover:text-gray-500')  : 'text-gray-500';
    $type = (!empty($color)) ? $color : $type;
    $presets = (in_array($type, ['error','warning', 'info', 'success'])) ? [
        'faint' => "bg-$type-100/80 text-$type-600",
        'dark' => "bg-$type-500 text-white",
        'icon' => [ 'faint' => "text-$type-600", 'dark' => "!text-$type-200" ]
    ] : [   // not error, warning, info, success
        'faint' => "bg-$type-200/70 text-$type-700",
        'dark' => "bg-$type-500 text-$type-100",
        'icon' => [ 'faint' => "text-$type-700", 'dark' => "!text-$type-100" ]
    ];
    $colours = [
        'faint' => ($type=='transparent') ? "bg-transparent border border-slate-300/80 text-slate-700" : $presets['faint'],
        'dark' => ($type=='transparent') ? "bg-transparent border border-slate-400 text-slate-700" : $presets['dark'],
        'icon' => [
            'faint' => ($type=='transparent') ? "text-slate-400" : $presets['icon']['faint'],
            'dark' => ($type=='transparent') ? "text-slate-400" : $presets['icon']['dark'],
        ]
    ];
@endphp

<div class="w-full bw-alert animate__animated animate__fadeIn rounded-md flex p-3  {{$colours[$shade] }} {{ $class }}">
    @if($show_icon)
        <div class="pt-[1px]">
            @if($icon !== '')
                <x-bladewind::icon :name="$icon" class="-mt-1 {{ $icon_avatar_css}}"/>
            @elseif($avatar !== '')
                <x-bladewind::avatar :image="$avatar" :show_ring="$show_ring" :size="$size"
                                     class="{{ $icon_avatar_css}}"/>
            @else
                <x-bladewind::modal-icon type="{{$type}}"
                                         class="!h-6 !w-6 -mt-1 {{ $colors[$shade][$type]['icon_color'] ??'' }}"/>
            @endif
        </div>
    @endif
    <div class="grow pl-2 pr-5">{{ $slot }}</div>
    @if($show_close_icon)
        <div class="text-right" onclick="this.parentElement.style.display='none'">
            <x-bladewind::icon name="x-mark"
                               class="h-6 w-6 p-1 cursor-pointer {{$close_icon_css}} hover:bg-white hover:rounded-full dark:hover:bg-slate-800 "/>
        </div>
    @endif
</div>
