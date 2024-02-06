<div class="w-full max-w-2xl">

    <form class="w-full">
        <div class="mb-5 space-y-12">
            <div class="pb-12 border-b border-gray-900/10">
                <div class="flex justify-between">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Calculateur d'impôts</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Suivant vos revenus nous calculons vos impôts</p>
                    </div>
                </div>
            </div>

            {{-- <div class="pb-12 border-b border-gray-900/10">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Personal Information</h2>
                <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p>

                <div class="grid grid-cols-1 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="first-name" class="block text-sm font-medium leading-6 text-gray-900">First
                            name</label>
                        <div class="mt-2">
                            <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="last-name" class="block text-sm font-medium leading-6 text-gray-900">Last
                            name</label>
                        <div class="mt-2">
                            <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-4">
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                            address</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" autocomplete="email"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="country" class="block text-sm font-medium leading-6 text-gray-900">Country</label>
                        <div class="mt-2">
                            <select id="country" name="country" autocomplete="country-name"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Mexico</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-full">
                        <label for="street-address" class="block text-sm font-medium leading-6 text-gray-900">Street
                            address</label>
                        <div class="mt-2">
                            <input type="text" name="street-address" id="street-address"
                                autocomplete="street-address"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2 sm:col-start-1">
                        <label for="city" class="block text-sm font-medium leading-6 text-gray-900">City</label>
                        <div class="mt-2">
                            <input type="text" name="city" id="city" autocomplete="address-level2"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="region" class="block text-sm font-medium leading-6 text-gray-900">State /
                            Province</label>
                        <div class="mt-2">
                            <input type="text" name="region" id="region" autocomplete="address-level1"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="postal-code" class="block text-sm font-medium leading-6 text-gray-900">ZIP /
                            Postal code</label>
                        <div class="mt-2">
                            <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="pb-12 border-b border-gray-900/10">
                <div class="grid grid-cols-6 mt-10 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <div class="col-span-3 sm:col-span-3">
                        <label for="revenu" class="block text-sm font-medium leading-6 text-gray-900">Revenus Net
                            Imposable</label>
                        <div class="mt-2">
                            <input min=0 type="number" wire:model.blur='revenu_net_imposable' name="revenu"
                                placeholder="Montant en euros"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <li>Revenu Net Imposable : {{ $revenu_net_imposable }}</li>
                        </div>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <label for="foncier" class="block text-sm font-medium leading-6 text-gray-900">Dont Revenus
                            Foncier</label>
                        <div class="mt-2">
                            <input wire:model.blur='revenu_foncier' type="number" min=0 name="foncier"
                                placeholder="Montant en euros"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                        <li>Revenus Foncier : {{ $revenu_foncier }}</li>
                    </div>
                    <div class="col-span-2 sm:col-span-3">
                        <div class="flex justify-between">
                            <label for="foncier" class="block text-sm font-medium leading-6 text-gray-900">Nombre de
                                parts fiscales</label>


                            <div x-data="{
                                popoverOpen: false,
                                popoverArrow: true,
                                popoverPosition: 'bottom',
                                popoverHeight: 0,
                                popoverOffset: 8,
                                popoverHeightCalculate() {
                                    this.$refs.popover.classList.add('invisible');
                                    this.popoverOpen = true;
                                    let that = this;
                                    $nextTick(function() {
                                        that.popoverHeight = that.$refs.popover.offsetHeight;
                                        that.popoverOpen = false;
                                        that.$refs.popover.classList.remove('invisible');
                                        that.$refs.popoverInner.setAttribute('x-transition', '');
                                        that.popoverPositionCalculate();
                                    });
                                },
                                popoverPositionCalculate() {
                                    if (window.innerHeight < (this.$refs.popoverButton.getBoundingClientRect().top + this.$refs.popoverButton.offsetHeight + this.popoverOffset + this.popoverHeight)) {
                                        this.popoverPosition = 'top';
                                    } else {
                                        this.popoverPosition = 'bottom';
                                    }
                                }
                            }" x-init="that = this;
                            window.addEventListener('resize', function() {
                                popoverPositionCalculate();
                            });
                            $watch('popoverOpen', function(value) {
                                if (value) {
                                    popoverPositionCalculate();
                                    document.getElementById('width').focus();
                                }
                            });" class="relative">

                                <a x-ref="popoverButton" @click="popoverOpen=!popoverOpen"
                                    class="flex items-center justify-center bg-white border rounded-full shadow-sm cursor-pointer w-7 h-7 hover:bg-neutral-100 focus-visible:ring-gray-400 focus-visible:ring-2 focus-visible:outline-none active:bg-white border-neutral-200/70">
                                    <svg class="w-3 h-3" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.5 3C4.67157 3 4 3.67157 4 4.5C4 5.32843 4.67157 6 5.5 6C6.32843 6 7 5.32843 7 4.5C7 3.67157 6.32843 3 5.5 3ZM3 5C3.01671 5 3.03323 4.99918 3.04952 4.99758C3.28022 6.1399 4.28967 7 5.5 7C6.71033 7 7.71978 6.1399 7.95048 4.99758C7.96677 4.99918 7.98329 5 8 5H13.5C13.7761 5 14 4.77614 14 4.5C14 4.22386 13.7761 4 13.5 4H8C7.98329 4 7.96677 4.00082 7.95048 4.00242C7.71978 2.86009 6.71033 2 5.5 2C4.28967 2 3.28022 2.86009 3.04952 4.00242C3.03323 4.00082 3.01671 4 3 4H1.5C1.22386 4 1 4.22386 1 4.5C1 4.77614 1.22386 5 1.5 5H3ZM11.9505 10.9976C11.7198 12.1399 10.7103 13 9.5 13C8.28967 13 7.28022 12.1399 7.04952 10.9976C7.03323 10.9992 7.01671 11 7 11H1.5C1.22386 11 1 10.7761 1 10.5C1 10.2239 1.22386 10 1.5 10H7C7.01671 10 7.03323 10.0008 7.04952 10.0024C7.28022 8.8601 8.28967 8 9.5 8C10.7103 8 11.7198 8.8601 11.9505 10.0024C11.9668 10.0008 11.9833 10 12 10H13.5C13.7761 10 14 10.2239 14 10.5C14 10.7761 13.7761 11 13.5 11H12C11.9833 11 11.9668 10.9992 11.9505 10.9976ZM8 10.5C8 9.67157 8.67157 9 9.5 9C10.3284 9 11 9.67157 11 10.5C11 11.3284 10.3284 12 9.5 12C8.67157 12 8 11.3284 8 10.5Z"
                                            fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                                    </svg>
                                </a>

                                <div x-ref="popover" x-show="popoverOpen" x-init="setTimeout(function() { popoverHeightCalculate(); }, 100);"
                                    x-trap.inert="popoverOpen" @click.away="popoverOpen=false;"
                                    @keydown.escape.window="popoverOpen=false"
                                    :class="{
                                        'top-0 mt-12': popoverPosition == 'bottom',
                                        'bottom-0 mb-12': popoverPosition ==
                                            'top'
                                    }"
                                    class="absolute w-[300px] max-w-lg -translate-x-1/2 left-1/2" x-cloak>
                                    <div x-ref="popoverInner" x-show="popoverOpen"
                                        class="w-full p-4 bg-white border rounded-md shadow-sm border-neutral-200/70">
                                        <div x-show="popoverArrow && popoverPosition == 'bottom'"
                                            class="absolute top-0 inline-block w-5 mt-px overflow-hidden -translate-x-2 -translate-y-2.5 left-1/2">
                                            <div
                                                class="w-2.5 h-2.5 origin-bottom-left transform rotate-45 bg-white border-t border-l rounded-sm">
                                            </div>
                                        </div>
                                        <div x-show="popoverArrow  && popoverPosition == 'top'"
                                            class="absolute bottom-0 inline-block w-5 mb-px overflow-hidden -translate-x-2 translate-y-2.5 left-1/2">
                                            <div
                                                class="w-2.5 h-2.5 origin-top-left transform -rotate-45 bg-white border-b border-l rounded-sm">
                                            </div>
                                        </div>
                                        <div class="grid gap-4">
                                            <div class="space-y-2">
                                                <h4 class="font-medium leading-none">Parts fiscales</h4>
                                                <p class="text-sm text-muted-foreground">Ajuster le nombre de parts
                                                    fiscale</p>
                                            </div>
                                            <div class="grid gap-2">
                                                <div class="grid items-center grid-cols-3 gap-4"><label
                                                        class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                                        for="width">Nombre d'enfants</label><input
                                                        class="flex w-full h-8 col-span-2 px-3 py-2 text-sm bg-transparent border rounded-md border-input ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-neutral-400 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                                        wire:model.live='nb_enfant' min=0 type="number"></div>
                                                <div class="">
                                                    <div x-data="{ isMaried: @entangle('isMaried').live }"
                                                        class="flex items-center justify-between w-full">
                                                        <input wire:model.live='isMaried' type="checkbox" name="switch"
                                                            class="hidden" :checked="isMaried">
                                                        <label
                                                            @click="$refs.switchButton.click(); $refs.switchButton.focus()"
                                                            :id="$id('switch')"
                                                            :class="{ 'text-blue-600': isMaried, 'text-gray-800': !isMaried }"
                                                            class="text-sm select-none" x-cloak>
                                                            Marié(e)
                                                        </label>

                                                        <button x-ref="switchButton" type="button"
                                                            @click="isMaried = ! isMaried;"
                                                            :class="isMaried ? 'bg-blue-600' : 'bg-neutral-200'"
                                                            class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                                            x-cloak>
                                                            <span
                                                                :class="isMaried ? 'translate-x-[18px]' : 'translate-x-0.5'"
                                                                class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                                                        </button>

                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div x-data="{ isAlone: @entangle('isAlone').live }"
                                                        class="flex items-center justify-between w-full">
                                                        <input wire:model.live='isAlone' type="checkbox" name="maried"
                                                            class="hidden" :checked="isAlone">
                                                        <label
                                                            @click="$refs.switchButton.click(); $refs.switchButton.focus()"
                                                            :id="$id('switch')"
                                                            :class="{ 'text-blue-600': isAlone, 'text-gray-800': !isAlone }"
                                                            class="text-sm select-none" x-cloak>
                                                            Célibataire / Divorcé(e) / Veuf(ve)
                                                        </label>

                                                        <button x-ref="switchButton" type="button"
                                                            @click="isAlone = ! isAlone;"
                                                            :class="isAlone ? 'bg-blue-600' : 'bg-neutral-200'"
                                                            class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                                            x-cloak>
                                                            <span
                                                                :class="isAlone ? 'translate-x-[18px]' : 'translate-x-0.5'"
                                                                class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                                                        </button>

                                                    </div>
                                                </div>
                                                <div class="">
                                                    <div x-data="{ isInvalide: @entangle('isInvalide').live }"
                                                        class="flex items-center justify-between w-full">
                                                        <input id="thisId" type="checkbox" name="switch"
                                                            class="hidden" wire:model.live='isInvalide'
                                                            type="checkbox" :checked="isInvalide">
                                                        <label
                                                            @click="$refs.switchButton.click(); $refs.switchButton.focus()"
                                                            :id="$id('switch')"
                                                            :class="{
                                                                'text-blue-600': isInvalide,
                                                                'text-gray-800': !
                                                                    isInvalide
                                                            }"
                                                            class="text-sm select-none" x-cloak>
                                                            Carte d'invalidité
                                                        </label>

                                                        <button x-ref="switchButton" type="button"
                                                            @click="isInvalide = ! isInvalide;"
                                                            :class="isInvalide ? 'bg-blue-600' : 'bg-neutral-200'"
                                                            class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10"
                                                            x-cloak>
                                                            <span
                                                                :class="isInvalide ? 'translate-x-[18px]' : 'translate-x-0.5'"
                                                                class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                                                        </button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>






                        </div>

                        <div class="mt-2">
                            <input wire:model.live='nb_part' type="number" min=1 name="nombre de parts fiscales"
                                placeholder="Parts fiscales du foyer"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <li>Nombre de part : {{ $nb_part }}</li>
                        </div>
                    </div>
                </div>
                {{-- <div class="mt-10 space-y-10">
                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-gray-900">By Email</legend>
                        <div class="mt-6 space-y-6">
                            <div class="relative flex gap-x-3">
                                <div class="flex items-center h-6">
                                    <input id="comments" name="comments" type="checkbox"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="comments" class="font-medium text-gray-900">Comments</label>
                                    <p class="text-gray-500">Get notified when someones posts a comment on a posting.
                                    </p>
                                </div>
                            </div>
                            <div class="relative flex gap-x-3">
                                <div class="flex items-center h-6">
                                    <input id="candidates" name="candidates" type="checkbox"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="candidates" class="font-medium text-gray-900">Candidates</label>
                                    <p class="text-gray-500">Get notified when a candidate applies for a job.</p>
                                </div>
                            </div>
                            <div class="relative flex gap-x-3">
                                <div class="flex items-center h-6">
                                    <input id="offers" name="offers" type="checkbox"
                                        class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-600">
                                </div>
                                <div class="text-sm leading-6">
                                    <label for="offers" class="font-medium text-gray-900">Offers</label>
                                    <p class="text-gray-500">Get notified when a candidate accepts or rejects an offer.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend class="text-sm font-semibold leading-6 text-gray-900">Push Notifications</legend>
                        <p class="mt-1 text-sm leading-6 text-gray-600">These are delivered via SMS to your mobile
                            phone.</p>
                        <div class="mt-6 space-y-6">
                            <div class="flex items-center gap-x-3">
                                <input id="push-everything" name="push-notifications" type="radio"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600">
                                <label for="push-everything"
                                    class="block text-sm font-medium leading-6 text-gray-900">Everything</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="push-email" name="push-notifications" type="radio"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600">
                                <label for="push-email" class="block text-sm font-medium leading-6 text-gray-900">Same
                                    as email</label>
                            </div>
                            <div class="flex items-center gap-x-3">
                                <input id="push-nothing" name="push-notifications" type="radio"
                                    class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-600">
                                <label for="push-nothing" class="block text-sm font-medium leading-6 text-gray-900">No
                                    push notifications</label>
                            </div>
                        </div>
                    </fieldset>
                </div> --}}
            </div>
        </div>

        <div class="flex justify-between">
            <div>
                <li>Décote : {{ $decote_imposition }} €</li>

                <li>Déduction du quotient familiale plafonné: {{ $plafond_quotient_familial }} €</li>

                <li>Contribution expetionnel sur les hauts revenus : {{ $impot_correction }} €</li>

            </div>
            <div class="text-right">
                <h2 class="text-2xl font-semibold leading-7 text-gray-900">Montant de votre impôt</h2>
                <p class="text-2xl font-semibold leading-7 text-gray-900">{{ $impot }} €</p>
                <p class="mt-1 text-sm leading-6 text-gray-600">Dont {{ $sociaux }} € de prélèvement
                    sociaux
                </p>
            </div>
        </div>



        {{-- <div class="flex items-center justify-end mt-6 gap-x-6">
            <button @click="$wire.prevent.calculImpot()" class="btn-primary">Calculer</button>
        </div> --}}
    </form>

</div>
