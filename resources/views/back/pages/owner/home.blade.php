@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Owner Home')
@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">

                <h4>Welcome, {{Auth::user()->name}} </h4>
            </div>
        </div>
    </div>
</div>
<script>

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var today = new Date();

var calendar = new FullCalendar.Calendar(calendarEl, {
  initialView: 'dayGridMonth',
  initialDate: today,
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },
  events: [
    {
      title: 'All Day Event',
      start: '2024-02-01'
    },
    {
      title: 'Long Event',
      start: '2024-02-07',
      end: '2024-02-10'
    },
    {
      groupId: '999',
      title: 'Repeating Event',
      start: '2024-02-09T16:00:00'
    },
    {
      groupId: '999',
      title: 'Repeating Event',
      start: '2024-02-16T16:00:00'
    },
    {
      title: 'Conference',
      start: '2024-02-11',
      end: '2024-02-13'
    },
    {
      title: 'Meeting',
      start: '2024-02-12T10:30:00',
      end: '2024-02-12T12:30:00'
    },
    {
      title: 'Lunch',
      start: '2024-02-12T12:00:00'
    },
    {
      title: 'Meeting',
      start: '2024-02-12T14:30:00'
    },
    {
      title: 'Birthday Party',
      start: '2024-02-13T07:00:00'
    },
    {
      title: 'Click for Google',
      url: 'https://google.com/',
      start: '2024-02-28'
    }
  ]
});
      calendar.render();
    });

  </script>

<div>
    <div class="row">
        <div class="col-md-12">
            <div class="pd-20 card-box mb-30">
                <div class="clearfix">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

