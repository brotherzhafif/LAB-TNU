@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js" crossorigin="anonymous"></script>
@endpush

<x-filament::page>
    <div class="flex flex-col md:flex-row gap-6">
        <div class="md:w-1/4 w-full mb-4">
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4">
                <div class="mb-4">
                    <div id="event-list-title" class="font-semibold text-primary-600 dark:text-primary-400"></div>
                    <ul id="event-list" class="ml-2 mt-1 space-y-1"></ul>
                </div>
            </div>
        </div>
        <div class="md:w-3/4 w-full">
            <div id="calendar" class="bg-white dark:bg-gray-900 rounded-lg shadow p-4"></div>
        </div>
    </div>

    <script>
        const allEvents = @json($events);

        function renderEventList(dateStr) {
            const list = document.getElementById('event-list');
            list.innerHTML = '';
            const events = allEvents.filter(ev => ev.start.startsWith(dateStr));
            if (events.length === 0) {
                list.innerHTML = '<li class="italic text-gray-400 dark:text-gray-500">Tidak ada jadwal.</li>';
            } else {
                events.forEach(ev => {
                    const li = document.createElement('li');
                    li.className = "text-sm flex items-center gap-2";
                    li.innerHTML = `
                        <span class="inline-block w-3 h-3 rounded-full" style="background: ${ev.color}"></span>
                        <span>${ev.title}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">
                            ${ev.start.substr(11, 5)} - ${ev.end.substr(11, 5)}
                        </span>
                    `;
                    list.appendChild(li);
                });
            }
        }

        function setEventListTitle(dateStr) {
            const title = document.getElementById('event-list-title');
            const date = new Date(dateStr);
            title.textContent = date.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'short', year: 'numeric' });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const today = new Date().toISOString().slice(0, 10);
            renderEventList(today);
            setEventListTitle(today);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: allEvents,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                dateClick: function (info) {
                    calendar.changeView('timeGridDay', info.dateStr);
                    renderEventList(info.dateStr);
                    setEventListTitle(info.dateStr);
                },
            });
            calendar.render();
        });
    </script>

    <style>
        #calendar {
            margin-top: 0;
        }

        /* FullCalendar theme adaptation for Filament dark mode */
        .dark #calendar .fc {
            background-color: #111827;
            color: #e5e7eb;
        }

        .dark #calendar .fc-toolbar-title,
        .dark #calendar .fc-col-header-cell-cushion,
        .dark #calendar .fc-daygrid-day-number {
            color: #f3f4f6;
        }

        .dark #calendar .fc-button {
            background-color: #1f2937;
            color: #f3f4f6;
            border-color: #374151;
        }

        .dark #calendar .fc-button-primary:not(:disabled).fc-button-active,
        .dark #calendar .fc-button-primary:not(:disabled):active {
            background-color: #2563eb;
            color: #fff;
        }

        .dark #calendar .fc-daygrid-day.fc-day-today {
            background-color: #1e293b;
        }

        .dark #calendar .fc-event {
            background-color: #2563eb !important;
            color: #fff !important;
        }

        .dark #calendar .fc-list-day-cushion,
        .dark #calendar .fc-cell-shaded {
            background-color: #1e293b !important;
            color: #f3f4f6 !important;
        }

        /* Hover effect for events in all views (light & dark) */
        #calendar .fc-event,
        #calendar .fc-list-event {
            transition: background 0.15s, color 0.15s;
        }

        #calendar .fc-event:hover,
        #calendar .fc-list-event:hover,
        #calendar tr.fc-list-event:hover {
            background-color: #e0e7ef !important;
            /* light blue-100 */
            color: #1e293b !important;
        }

        .dark #calendar .fc-event:hover,
        .dark #calendar .fc-list-event:hover,
        .dark #calendar tr.fc-list-event:hover {
            background-color: #334155 !important;
            /* slate-700 */
            color: #f3f4f6 !important;
        }

        /* Hover for dayGrid and timeGrid cells */
        #calendar .fc-daygrid-day:hover,
        #calendar .fc-timegrid-slot:hover {
            background-color: #f1f5f9 !important;
            /* slate-100 */
        }

        .dark #calendar .fc-daygrid-day:hover,
        .dark #calendar .fc-timegrid-slot:hover {
            background-color: #1e293b !important;
        }
    </style>
</x-filament::page>