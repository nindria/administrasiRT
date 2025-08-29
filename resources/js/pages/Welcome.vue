<!-- resources/js/Pages/Landing/Index.vue -->
<script setup lang="ts">
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

interface Event {
    id: number;
    title: string;
    description: string;
    image: string | null;
}

interface Banner {
    id: number;
    title: string;
    description: string;
    image: string;
    is_active: boolean;
}

const props = defineProps<{
    events: Event[];
    banner?: Banner;
}>();

/* Sample data - replace with dynamic props from your Laravel controller if needed */
const features = [
    { title: 'Business insurance', icon: '/images/icons/business.svg' },
    { title: 'Personal insurance', icon: '/images/icons/personal.svg' },
    { title: 'Information for Brokers', icon: '/images/icons/broker.svg' },
    { title: 'Secured by Legal protection', icon: '/images/icons/legal.svg' },
    { title: 'Money Back Guarantee', icon: '/images/icons/money.svg' },
]

const partners = [
    { name: 'envato', logo: '/images/partners/envato.svg' },
    { name: 'slack', logo: '/images/partners/slack.svg' },
    { name: 'figma', logo: '/images/partners/figma.svg' },
    { name: 'sketch', logo: '/images/partners/sketch.svg' },
]

const services = [
    { title: 'Life Insurance', desc: 'Our customers get solutions and business opportunities instead of just projects.' },
    { title: 'Home Insurance', desc: 'Protect what matters most — your home and family.' },
    { title: 'Health Insurance', desc: 'Flexible plans with dependable support.' },
    { title: 'Business Insurance', desc: 'Commercial protection tailored for growth.' },
]

/* simple slider state to update progress bar */
const idx = ref(0)
const visibleCount = ref(4) // used to calculate progress; adjust with responsive logic if needed

function prevCard() {
    idx.value = Math.max(0, idx.value - 1)
}
function nextCard() {
    idx.value = Math.min(Math.max(0, services.length - visibleCount.value), idx.value + 1)
}

const sliderProgress = computed(() => {
    if (services.length <= visibleCount.value) return 100
    const max = services.length - visibleCount.value
    if (max <= 0) return 100
    return ((idx.value / max) * 100).toFixed(2)
})
</script>
<template>
    <div class="font-inter antialiased text-slate-800">
        
        <!-- Header / Nav -->
        <header class="bg-white">
            <div class="max-w-7xl mx-auto px-4 lg:px-8 flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-sm"></div>
                    <div class="font-bold text-lg">si</div>
                </div>

                <nav class="hidden lg:flex items-center gap-6 text-sm">
                    <a class="hover:text-blue-600" href="#home">Home</a>
                    <a class="hover:text-blue-600" href="#insurance">Insurance</a>
                    <a class="hover:text-blue-600" href="#about">About</a>
                    <a class="hover:text-blue-600" href="#services">Services</a>
                    <a class="hover:text-blue-600" href="#contact">Contact</a>
                </nav>

                <div class="hidden md:flex items-center gap-4">
                    <div class="text-sm text-slate-600">Call: (210) 123-451</div>
                    <Link :href="route('login')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">Login</Link>
                </div>

                <button class="md:hidden p-2 rounded border">☰</button>
            </div>
        </header>

        <!-- HERO -->
        <section id="home" class="relative">
            <!-- Background image + overlay shapes -->
            <div class="h-[560px] relative overflow-hidden">
                <img 
                    v-if="props.banner?.image" 
                    :src="`/storage/${props.banner.image}`" 
                    alt="hero" 
                    class="object-cover w-full h-full brightness-75" 
                />
                <img 
                    v-else 
                    src="/images/hero.jpg" 
                    alt="hero" 
                    class="object-cover w-full h-full brightness-75" 
                />
                <!-- translucent geometric shapes (decorative) -->
                <div aria-hidden
                    class="absolute inset-0 bg-gradient-to-tr from-blue-800/40 via-indigo-700/20 to-transparent mix-blend-screen pointer-events-none">
                </div>
                <div aria-hidden
                    class="absolute -left-40 -top-10 w-96 h-96 bg-gradient-to-br from-blue-500/30 to-indigo-600/20 rotate-45 opacity-60 blur-[2px]">
                </div>
                <div aria-hidden class="absolute right-0 top-36 w-80 h-80 bg-white/5 rounded-full blur-xl"></div>

                <!-- Hero content -->
                <div class="absolute inset-0 max-w-7xl mx-auto px-6 lg:px-8 flex items-center h-full">
                    <div class="w-full grid lg:grid-cols-2 gap-8 items-center">
                        <div class="text-white">
                            <div
                                class="inline-block bg-white/90 text-slate-900 px-3 py-1 rounded text-xs font-semibold mb-4">
                                We are Insurance Solution</div>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight drop-shadow-lg">
                                Supporting Your Business <br class="hidden md:block" /> In a Crisis.
                            </h1>
                            <p class="mt-4 text-lg max-w-xl text-white/80">We’re one of the largest general insurers in
                                the UK, offering a range of personal and commercial insurance solutions.</p>

                            <div class="mt-8 flex items-center gap-4">
                                <button class="bg-black/90 text-white px-5 py-3 rounded-lg font-semibold shadow">Our
                                    Team</button>
                                <a class="text-white/90 px-4 py-3 rounded-lg border border-white/20">Learn More</a>
                            </div>
                        </div>

                        <!-- right - decorative / optional image -->
                        
                    </div>
                </div>

                <!-- Floating features cards (5 cards centered at bottom of hero) -->
                <div class="absolute left-0 right-0 -bottom-10 flex justify-center">
                    <div class="max-w-6xl w-full px-4">
                        <div class="mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-6">
                            <div v-for="(f, idx) in features" :key="idx"
                                class="bg-white rounded-xl shadow-lg p-6 text-center transform -translate-y-6 hover:translate-y-0 transition">
                                <div
                                    class="w-14 h-14 mx-auto flex items-center justify-center rounded-lg bg-blue-50 mb-3">
                                    <!-- use provided icon or fallback -->
                                    <img v-if="f.icon" :src="f.icon" alt="" class="w-8 h-8" />
                                    <div v-else class="text-xl">🏷️</div>
                                </div>
                                <div class="text-sm font-semibold">{{ f.title }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- decorative faint divider and partners row below hero -->
            <div class="bg-white pt-20 pb-8">
                <div class="max-w-7xl mx-auto px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-sm text-slate-500">Join over +15,000 happy clients!</div>
                        <div class="hidden md:block h-px flex-1 mx-6 bg-slate-100"></div>
                        <div class="flex items-center gap-6">
                            <img v-for="(p, i) in partners" :key="i" :src="p.logo" :alt="p.name"
                                class="h-6 opacity-80" />
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ABOUT -->
        <section id="about" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <h1 class="text-2xl font-bold text-center mb-8">WELCOME TO SIRUWA 07!</h1>
                <p class="text-xl text-center mb-8">integrated citizen registration website</p>

                <!-- Events Section -->
                <div v-if="events && events.length > 0" class="mt-16">
                    <h3 class="text-2xl font-bold text-center mb-8">Upcoming Events</h3>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link v-for="(event, index) in events.slice(0, 3)" :key="index" 
                             :href="route('events.show', event.id)"
                             class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition block">
                            <div class="h-48 bg-blue-100 flex items-center justify-center">
                                <img v-if="event.image" :src="`/storage/${event.image}`" :alt="event.title" class="w-full h-full object-cover" />
                                <div v-else class="text-blue-600 text-4xl">📅</div>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-lg mb-2">{{ event.title }}</h4>
                                <p class="text-sm text-slate-600">{{ event.description }}</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- SERVICES (with subtle patterned background) -->
        <section id="services" class="py-20 bg-gray-50 relative overflow-hidden">
            <!-- optional diagonal pattern -->
            <div aria-hidden
                class="absolute inset-0 bg-[linear-gradient(90deg,#ffffff00_0%,#f3f4f6_100%)] pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="text-center mb-12">
                    <p class="text-sm text-blue-600 font-semibold">Our Awesome Services</p>
                    <h3 class="text-3xl font-extrabold mt-2">Choose The Best Insurance in your life</h3>
                </div>

                <!-- slider style cards -->
                <div class="relative">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div v-for="(s, i) in services" :key="i"
                            class="bg-white rounded-2xl p-6 shadow hover:shadow-xl transition">
                            <div
                                class="w-16 h-16 rounded-full bg-blue-50 grid place-items-center text-blue-600 text-2xl mb-4">
                                🏠</div>
                            <h4 class="font-bold mb-2">{{ s.title }}</h4>
                            <p class="text-sm text-slate-500">{{ s.desc }}</p>
                        </div>
                    </div>

                    <!-- slider controls (visual) -->
                    <div class="mt-8 flex items-center justify-between">
                        <button @click="prevCard"
                            class="w-10 h-10 rounded-full border grid place-items-center">‹</button>
                        <div class="flex-1 mx-6">
                            <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-2 bg-blue-600 rounded-full" :style="{ width: sliderProgress + '%' }">
                                </div>
                            </div>
                        </div>
                        <button @click="nextCard"
                            class="w-10 h-10 rounded-full border grid place-items-center">›</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- FOOTER -->
        <footer id="contact" class="bg-white border-t mt-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 grid md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-tr from-blue-500 to-indigo-600 rounded-sm"></div>
                        <div class="font-bold">Alico</div>
                    </div>
                    <p class="text-sm text-slate-600">We focus on opportunities that maximize all times like a plumber
                        who fixes your pipes.</p>
                </div>

                <div>
                    <h5 class="font-semibold mb-3">Contact</h5>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li>Phone: +61 124 3467 2345</li>
                        <li>Email: support@alico.com</li>
                        <li>Address: 380 St Kilda Rd, Melbourne</li>
                    </ul>
                </div>

                <div>
                    <h5 class="font-semibold mb-3">Legal</h5>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li>Privacy</li>
                        <li>Terms</li>
                        <li>Cookies</li>
                    </ul>
                </div>
            </div>

            <div class="text-center text-slate-500 py-6 border-t">© 2025 Alico. All rights reserved.</div>
        </footer>
    </div>
</template>
