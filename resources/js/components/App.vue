<!--
  RESPONSIVE LAYOUT - Mobile-first design
  Breakpoints: sm(640px), md(768px), lg(1024px), xl(1280px)
  Mobile: Sidebar hidden, top navbar with hamburger menu
  Tablet+: Traditional sidebar layout
-->
<template>
  <div class="flex h-screen w-screen overflow-hidden bg-gray-900 light:bg-gray-100" :class="{ 'light-mode': !themeStore.isDarkMode }">
    
    <!-- Mobile Overlay (shown when mobile menu is open) - z-index above navbar -->
    <div 
      v-if="isAuthed && mobileMenuOpen" 
      class="fixed inset-0 bg-black/60 z-[10001] lg:hidden"
      @click="mobileMenuOpen = false"
    ></div>

    <!-- Sidebar - Hidden on mobile, shown on lg+ - z-index above navbar on mobile -->
    <aside v-if="isAuthed"
      :class="[
        'fixed lg:relative z-[10002] lg:z-20 h-full flex-shrink-0 overflow-y-auto transition-all duration-300 glass-panel border shadow-xl',
        'transform lg:transform-none',
        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        collapsed ? 'w-20' : 'w-64',
        themeStore.isDarkMode ? 'border-white/10' : 'border-gray-200'
      ]"
    >
      <!-- Mobile close button -->
      <button 
        @click="mobileMenuOpen = false"
        class="lg:hidden absolute top-4 right-4 p-2 rounded-full hover:bg-white/10"
        :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <div class="p-4 flex items-center justify-between">
        <h1 v-if="!collapsed" :class="['text-xl font-bold tracking-wide', themeStore.isDarkMode ? 'text-white' : 'text-gray-900']">Inventario<p class="text-orange-400" style="display: inline;">X</p>press</h1>
        <button
          :class="[
            'rounded-full p-2 hover:scale-105 transition transform border hidden lg:block',
            themeStore.isDarkMode ? 'bg-white/10 border-white/20 text-white/90' : 'bg-blue-100/50 border-blue-200 text-gray-900'
          ]"
          @click="toggleSidebar"
          aria-label="Toggle sidebar"
          title="Contraer/expandir"
        >
          <!-- Heroicon: Bars-3 -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M3.75 5.25A.75.75 0 014.5 4.5h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm0 7.5a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75zm.75 6a.75.75 0 000 1.5h15a.75.75 0 000-1.5h-15z" clip-rule="evenodd"/>
          </svg>
        </button>
      </div>

      <nav class="mt-2 space-y-1" @click="closeMobileMenuOnNavigate">
        <router-link to="/dashboard" :class="['menu-item', isActiveExact('/dashboard') ? 'menu-item-active' : '']">
          <span class="icon">
            <!-- Home Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 3.841a.75.75 0 011.06 0l8.25 8.25a.75.75 0 01-1.06 1.06l-.22-.22V19.5A2.25 2.25 0 0117.25 21h-1.5a.75.75 0 01-.75-.75V15a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75v5.25a.75.75 0 01-.75.75h-1.5A2.25 2.25 0 013.5 19.5v-6.57l-.22.22a.75.75 0 11-1.06-1.06l8.25-8.25z"/></svg>
          </span>
          <span v-if="!collapsed">Página Principal</span>
        </router-link>

        <router-link to="/almacenes" :class="['menu-item', isActivePrefix('/almacenes') ? 'menu-item-active' : '']">
          <span class="icon">
            <!-- Building Storefront -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7.5V6a3 3 0 013-3h12a3 3 0 013 3v1.5H3z"/><path fill-rule="evenodd" d="M3 9h18v6.75A3.75 3.75 0 0117.25 19.5h-10.5A3.75 3.75 0 013 15.75V9zm4.5 2.25A.75.75 0 018.25 12h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 12.75z" clip-rule="evenodd"/></svg>
          </span>
          <span v-if="!collapsed">Almacenes</span>
        </router-link>

        <details :class="['my-1', collapsed ? 'mx-1' : 'mx-2']">
          <summary :class="['cursor-pointer select-none flex items-center rounded-lg hover:bg-white/10', collapsed ? 'justify-center gap-0 px-1 py-2' : 'gap-3 px-3 py-2']">
            <span class="icon w-6 h-6 flex items-center justify-center" :style="collapsed ? '' : 'margin-left:2px'">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M13.5 4.5a.75.75 0 01.75-.75h5a.75.75 0 010 1.5H16.06l6.22 6.22a.75.75 0 11-1.06 1.06L15 6.31V9.75a.75.75 0 01-1.5 0v-5z"/>
                <path d="M10.5 19.5a.75.75 0 01-.75.75h-5a.75.75 0 010-1.5H7.94L1.72 12.03a.75.75 0 111.06-1.06L9 17.69V14.25a.75.75 0 011.5 0v5z"/>
              </svg>
            </span>
            <span v-if="!collapsed">Movimientos</span>
          </summary>
          <ul :class="[collapsed ? 'ml-2' : 'ml-7', 'mt-1 space-y-1']">
            <li>
              <router-link to="/pedidos" :class="['menu-item', isActivePrefix('/pedidos') ? 'menu-item-active' : '']">
                <span class="icon w-6 h-6 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M2.25 7.5A2.25 2.25 0 014.5 5.25H15a.75.75 0 01.75.75v10.5H18a2.25 2.25 0 100 1.5h-1.5a2.25 2.25 0 10-4.5 0H6.75a2.25 2.25 0 10-4.5 0V7.5zM18 9h2.19c.199 0 .39.079.53.22l1.06 1.06c.141.142.22.332.22.53V15a.75.75 0 01-.75.75H18V9z"/></svg></span>
                <span v-if="!collapsed">Pedidos</span>
              </router-link>
            </li>
            <li>
              <router-link to="/salidas" :class="['menu-item', isActivePrefix('/salidas') ? 'menu-item-active' : '']">
                <span class="icon w-6 h-6 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 15.75a.75.75 0 01.75-.75h4.5a.75.75 0 110 1.5h-4.5A.75.75 0 013 15.75zM9 15.75a.75.75 0 01.75-.75h11.5a.75.75 0 110 1.5H9.75a.75.75 0 01-.75-.75zM12 9V19.5a.75.75 0 001.5 0V9h2.69a.75.75 0 00.53-1.28l-3.439-3.44a.75.75 0 00-1.061 0L8.78 7.72A.75.75 0 009.31 9H12z"/></svg></span>
                <span v-if="!collapsed">Salidas</span>
              </router-link>
            </li>
          </ul>
        </details>

        <details :class="['my-1', collapsed ? 'mx-1' : 'mx-2']">
          <summary :class="['cursor-pointer select-none flex items-center rounded-lg hover:bg-white/10', collapsed ? 'justify-center gap-0 px-1 py-2' : 'gap-3 px-3 py-2']">
            <span class="icon w-6 h-6 flex items-center justify-center" :style="collapsed ? '' : 'margin-left:2px'">
              <!-- Inventory SVG provided by user, adapted to currentColor -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 256 230" fill="currentColor" aria-hidden="true">
                <path d="M61.2,106h37.4v31.2H61.2V106z M61.2,178.7h37.4v-31.2H61.2V178.7z M61.2,220.1h37.4v-31.2H61.2V220.1z M109.7,178.7H147
    v-31.2h-37.4V178.7z M109.7,220.1H147v-31.2h-37.4V220.1z M158.2,188.9v31.2h37.4v-31.2H158.2z M255,67.2L128.3,7.6L1.7,67.4
    l7.9,16.5l16.1-7.7v144h18.2V75.6h169v144.8h18.2v-144l16.1,7.5L255,67.2z"/>
              </svg>
            </span>
            <span v-if="!collapsed">Inventario</span>
          </summary>
          <ul :class="[collapsed ? 'ml-2' : 'ml-7', 'mt-1 space-y-1']">
            <li>
              <router-link to="/materiales" :class="['menu-item', isActivePrefix('/materiales') ? 'menu-item-active' : '']">
                <span class="icon w-6 h-6 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11.7 1.553a1 1 0 01.6 0l8 2.4A1 1 0 0121 4.9v7.2a1 1 0 01-.7.955l-8 2.4a1 1 0 01-.6 0l-8-2.4A1 1 0 013 12.1V4.9a1 1 0 01.7-.947l8-2.4z"/></svg></span>
                <span v-if="!collapsed">Materiales</span>
              </router-link>
            </li>
            <li>
              <router-link to="/stock" :class="['menu-item', isActivePrefix('/stock') ? 'menu-item-active' : '']">
                <span class="icon w-6 h-6 flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3.75 3a.75.75 0 000 1.5H5.25V21a.75.75 0 001.5 0V4.5h2.25V21a.75.75 0 001.5 0V4.5h2.25V21a.75.75 0 001.5 0V4.5h2.25V21a.75.75 0 001.5 0V4.5H21A.75.75 0 0021 3h-17.25z"/></svg></span>
                <span v-if="!collapsed">Stock</span>
              </router-link>
            </li>
          </ul>
        </details>

        <details :class="['my-1', collapsed ? 'mx-1' : 'mx-2']">
          <summary :class="['cursor-pointer select-none flex items-center rounded-lg hover:bg-white/10', collapsed ? 'justify-center gap-0 px-1 py-2' : 'gap-3 px-3 py-2']">
            <span class="icon" :style="collapsed ? 'margin-left:-4px' : 'margin-left:2px'">
              <!-- Map icon provided by user, adapted to currentColor -->
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M8.684 3.051a1 1 0 0 1 .632 0L15 4.946l4.367-1.456A2 2 0 0 1 22 5.387V17.28a2 2 0 0 1-1.367 1.898l-5.317 1.772a1 1 0 0 1-.632 0L9 19.054 4.632 20.51A2 2 0 0 1 2 18.613V6.72a2 2 0 0 1 1.368-1.898L8.684 3.05zM10 17.28l4 1.334V6.72l-4-1.334V17.28zM8 5.387L4 6.721v11.892l4-1.334V5.387zm8 1.334v11.892l4-1.334V5.387l-4 1.334z"/>
              </svg>
            </span>
            <span v-if="!collapsed">Lugares</span>
          </summary>
          <ul :class="[collapsed ? 'ml-2' : 'ml-7', 'mt-1 space-y-1']">
            <li>
              <router-link to="/ciudades" :class="['menu-item', isActivePrefix('/ciudades') ? 'menu-item-active' : '']">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25a6.75 6.75 0 00-6.75 6.75c0 4.312 4.78 9.146 6.048 10.345a1 1 0 001.404 0C14.97 18.146 18.75 13.312 18.75 9A6.75 6.75 0 0012 2.25zM12 12a3 3 0 110-6 3 3 0 010 6z" clip-rule="evenodd"/></svg></span>
                <span v-if="!collapsed">Ciudades</span>
              </router-link>
            </li>
            <li>
              <router-link to="/calles" :class="['menu-item', isActivePrefix('/calles') ? 'menu-item-active' : '']">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M10 2h4l1 20h-6L10 2zM3 22h18v1H3v-1z"/></svg></span>
                <span v-if="!collapsed">Calles</span>
              </router-link>
            </li>
            <li>
              <router-link to="/tramos" :class="['menu-item', isActivePrefix('/tramos') ? 'menu-item-active' : '']">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h4v12H4V6zm6 3h4v9h-4V9zm6-5h4v14h-4V4z"/></svg></span>
                <span v-if="!collapsed">Tramos</span>
              </router-link>
            </li>
            <li>
              <router-link to="/tramo-calle" :class="['menu-item', isActivePrefix('/tramo-calle') ? 'menu-item-active' : '']">
                <span class="icon"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 12a4 4 0 014-4h3a1 1 0 010 2h-3a2 2 0 100 4h3a1 1 0 010 2h-3a4 4 0 01-4-4zm-4 0a4 4 0 004 4h1a1 1 0 000-2H8a2 2 0 110-4h1a1 1 0 000-2H8a4 4 0 00-4 4z"/></svg></span>
                <span v-if="!collapsed">Tramo-Calle</span>
              </router-link>
            </li>
          </ul>
        </details>

        <router-link to="/instalaciones" :class="['menu-item', isActivePrefix('/instalaciones') ? 'menu-item-active' : '']">
          <span class="icon w-6 h-6 flex items-center justify-center">
            <!-- Wrench -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M21.707 20.293l-5.387-5.387a6.5 6.5 0 11-1.414-1.414l5.387 5.387a1 1 0 001.414 1.414z"/></svg>
          </span>
          <span v-if="!collapsed">Tramos de red</span>
        </router-link>

        <router-link v-if="isAdmin" to="/usuarios" :class="['menu-item', isActivePrefix('/usuarios') ? 'menu-item-active' : '']">
          <span class="icon w-6 h-6 flex items-center justify-center">
            <!-- Users -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 15 15" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 0.875C5.49797 0.875 3.875 2.49797 3.875 4.5C3.875 6.15288 4.98124 7.54738 6.49373 7.98351C5.2997 8.12901 4.27557 8.55134 3.50407 9.31167C2.52216 10.2794 2.02502 11.72 2.02502 13.5999C2.02502 13.8623 2.23769 14.0749 2.50002 14.0749C2.76236 14.0749 2.97502 13.8623 2.97502 13.5999C2.97502 11.8799 3.42786 10.7206 4.17091 9.9883C4.91536 9.25463 6.02674 8.87499 7.49995 8.87499C8.97317 8.87499 10.0846 9.25463 10.8291 9.98831C11.5721 10.7206 12.025 11.8799 12.025 13.5999C12.025 13.8623 12.2376 14.0749 12.5 14.0749C12.7623 14.075 12.975 13.8623 12.975 13.6C12.975 11.72 12.4778 10.2794 11.4959 9.31166C10.7244 8.55135 9.70025 8.12903 8.50625 7.98352C10.0187 7.5474 11.125 6.15289 11.125 4.5C11.125 2.49797 9.50203 0.875 7.5 0.875ZM4.825 4.5C4.825 3.02264 6.02264 1.825 7.5 1.825C8.97736 1.825 10.175 3.02264 10.175 4.5C10.175 5.97736 8.97736 7.175 7.5 7.175C6.02264 7.175 4.825 5.97736 4.825 4.5Z"/>
            </svg>
          </span>
          <span v-if="!collapsed">Usuarios</span>
        </router-link>

        
      </nav>
    </aside>

    <!-- Main Area -->
    <div class="flex-1 flex flex-col overflow-visible min-h-0">
      <!-- Navbar - Responsive with hamburger on mobile -->
      <nav v-if="isAuthed" :class="[
        'glass-panel mx-2 sm:mx-4 my-2 sm:my-4 rounded-xl sm:rounded-2xl px-3 sm:px-6 py-3 sm:py-4 flex justify-between items-center shadow-2xl border backdrop-saturate-150 relative z-[10000]',
        themeStore.isDarkMode ? 'border-white/10' : 'border-gray-200'
      ]">
        <!-- Left side: Hamburger (mobile) + Title -->
        <div class="flex items-center gap-3">
          <!-- Mobile hamburger menu button -->
          <button 
            @click="mobileMenuOpen = true"
            class="lg:hidden p-2 rounded-lg hover:bg-white/10"
            :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <h2 :class="['text-base sm:text-lg font-semibold', themeStore.isDarkMode ? 'text-white/90' : 'text-gray-900']">
            <span class="hidden sm:inline">Menú</span>
            <span class="sm:hidden">Inventario<span class="text-orange-400">X</span>press</span>
          </h2>
        </div>

        <!-- Right side: Actions -->
        <div class="flex items-center gap-1 sm:gap-3">
          <!-- Dark Mode Toggle - Hidden on xs, shown on sm+ -->
          <div class="hidden sm:flex items-center mr-2">
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" :checked="themeStore.isDarkMode" @click="themeStore.toggleDarkMode()" class="sr-only peer">
              <div :class="[
                'w-11 h-6 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all',
                themeStore.isDarkMode ? 'bg-cyan-600 after:border-gray-300' : 'bg-blue-300 after:border-white'
              ]"></div>
            </label>
            <span :class="['ml-2 text-sm font-medium hidden md:inline', themeStore.isDarkMode ? 'text-white/80' : 'text-gray-900']">
              {{ themeStore.isDarkMode ? 'Oscuro' : 'Claro' }}
            </span>
          </div>

          <!-- Mobile theme toggle (icon only) -->
          <button 
            @click="themeStore.toggleDarkMode()"
            class="sm:hidden p-2 rounded-lg hover:bg-white/10"
            :class="themeStore.isDarkMode ? 'text-white' : 'text-gray-900'"
          >
            <svg v-if="themeStore.isDarkMode" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
            </svg>
          </button>

          <!-- User Menu -->
          <details ref="userMenu" class="relative z-[10001]" v-click-outside="closeUserMenu">
            <summary :class="[
              'list-none flex items-center gap-1 sm:gap-2 select-none px-2 sm:px-3 py-2 rounded-lg cursor-pointer',
              themeStore.isDarkMode ? 'hover:bg-white/10' : 'hover:bg-blue-100'
            ]">
              <div :class="[
                'w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center overflow-hidden',
                themeStore.isDarkMode ? 'bg-white/10' : 'bg-blue-200'
              ]">
                <img v-if="auth.user?.ruta_imagen" :src="auth.user.ruta_imagen" alt="avatar" class="w-full h-full object-cover" />
                <!-- Default user icon -->
                <svg v-else xmlns="http://www.w3.org/2000/svg" :class="['h-4 w-4', themeStore.isDarkMode ? '' : 'text-gray-900']" viewBox="0 0 52 52" fill="currentColor" aria-hidden="true">
                  <path d="M50,43v2.2c0,2.6-2.2,4.8-4.8,4.8H6.8C4.2,50,2,47.8,2,45.2V43c0-5.8,6.8-9.4,13.2-12.2 c0.2-0.1,0.4-0.2,0.6-0.3c0.5-0.2,1-0.2,1.5,0.1c2.6,1.7,5.5,2.6,8.6,2.6s6.1-1,8.6-2.6c0.5-0.3,1-0.3,1.5-0.1 c0.2,0.1,0.4,0.2,0.6,0.3C43.2,33.6,50,37.1,50,43z M26,2c6.6,0,11.9,5.9,11.9,13.2S32.6,28.4,26,28.4s-11.9-5.9-11.9-13.2 S19.4,2,26,2z"/>
                </svg>
              </div>
              <span :class="['hidden md:inline font-medium', themeStore.isDarkMode ? 'text-white/80' : 'text-gray-900']">{{ auth.user?.nombre || 'Usuario' }}</span>
            </summary>
            <ul :class="[
              'absolute right-0 mt-2 min-w-[180px] sm:min-w-[200px] rounded-xl border shadow-xl p-1 z-[9999]',
              themeStore.isDarkMode ? 'bg-gray-800 border-white/10 text-white' : 'bg-white border-gray-200 text-gray-900'
            ]">
              <li>
                <router-link to="/perfil" :class="[
                  'block px-3 py-2 rounded-lg text-sm sm:text-base',
                  themeStore.isDarkMode ? 'hover:bg-white/10' : 'hover:bg-blue-50'
                ]">Mi perfil</router-link>
              </li>
              <li>
                <button :class="[
                  'w-full text-left px-3 py-2 rounded-lg text-sm sm:text-base',
                  themeStore.isDarkMode ? 'hover:bg-white/10' : 'hover:bg-blue-50'
                ]" @click="goLogout">Cerrar sesión</button>
              </li>
            </ul>
          </details>
        </div>
      </nav>

      <!-- Content -->
      <main :class="[
        'flex-1 min-h-0 overflow-auto',
        themeStore.isDarkMode ? 'page-bg' : 'bg-blue-50/80',
        !themeStore.isDarkMode && ['/', '/dashboard', '/contacto', '/terminos', '/privacidad'].includes(route.path) ? 'allow-scroll' : ''
      ]">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, onMounted } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useThemeStore } from '../stores/theme';

const collapsed = ref(false);
const mobileMenuOpen = ref(false);

function toggleSidebar(){ collapsed.value = !collapsed.value; }
function closeMobileMenuOnNavigate(e) {
  // Close mobile menu when clicking on a router-link
  if (e.target.closest('a')) {
    mobileMenuOpen.value = false;
  }
}
const route = useRoute()
const router = useRouter()
const baseCls = 'text-gray-300 hover:bg-gray-700 hover:text-white'
const activeCls = 'bg-orange-500 text-white font-semibold'
const auth = useAuthStore();
const themeStore = useThemeStore();
const isAuthed = computed(()=>auth.isAuthenticated);
const isAdmin = computed(()=> Number(auth.user?.rol) === 1);
const isProfile = computed(()=> route.path === '/perfil');
const userMenu = ref(null);
function closeUserMenu(){
  if (userMenu.value) {
    userMenu.value.open = false;
  }
}

async function goLogout(){
  await auth.logout();
  router.push('/');
}

function isActivePrefix(prefix) {
  // Activo para /prefix y cualquier subruta (/prefix/crear, etc)
  return route.path === prefix || route.path.startsWith(prefix + '/')
}

function isActiveExact(path) {
  return route.path === path
}

watch(isProfile, (val)=>{
  if (val && userMenu.value) {
    userMenu.value.open = false;
  }
});

// Initialize auth and theme on component mount
onMounted(async () => {
  try {
    // Initialize theme
    themeStore.initTheme();
    // Initialize auth store on app mount
    await auth.initialize();
    
    // Special handling for user ID 3 - ensure role is set to 1 (admin)
    if (auth.user?.id === 3) {
      console.log('User ID 3 detected, ensuring admin role (1)');
      auth.setUserRole(1);
    }
    
    // Verify role is set correctly
    console.log('Current user:', auth.user);
    console.log('Current role:', auth.user?.rol);
    
    // Redirect to login if not authenticated and not on a public route
    const publicRoutes = ['/login', '/register', '/']; // Añadimos '/' como ruta pública
    if (!auth.isAuthenticated && !publicRoutes.includes(route.path)) {
      router.push('/');
    }
  } catch (error) {
    console.error('Authentication initialization error:', error);
  }
});
</script>

<style scoped>
/* Estilos para el modo oscuro (por defecto) */
.glass-panel {
  background: rgba(17, 24, 39, 0.7);
  backdrop-filter: blur(10px);
}

/* Estilos para el modo claro */
:global(.light-mode) .glass-panel {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
}

/* Estilos para los elementos del menú */
.menu-item {
  @apply flex items-center gap-3 px-3 py-2 rounded-lg transition-all;
}

:global(.light-mode) .menu-item {
  @apply text-gray-900 hover:bg-blue-100 hover:text-gray-900 font-semibold;
}

:global(.dark) .menu-item, .menu-item {
  @apply text-gray-300 hover:bg-white/10 hover:text-white;
}

/* Estilos para elementos activos del menú */
:global(.light-mode) .menu-item-active {
  @apply bg-blue-500 text-white;
}

:global(.dark) .menu-item-active, .menu-item-active {
  @apply bg-orange-500 text-white;
}

/* Transiciones para cambios de tema */
* {
  transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

/* Mejoras de legibilidad para modo claro */
:global(.light-mode) h1, :global(.light-mode) h2, :global(.light-mode) h3 {
  color: #111827; /* gray-900 */
}

:global(.light-mode) p, :global(.light-mode) span, :global(.light-mode) div {
  color: #1e3a8a; /* blue-900 */
}

:global(.light-mode) .text-white {
  color: #1e3a8a !important; /* blue-900 */
}

:global(.light-mode) .text-gray-400 {
  color: #1e40af !important; /* blue-800 */
}
</style>
