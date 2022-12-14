<?php
/**
 * @var $item CampaignEntity
 */
?>
@extends('layouts.admin')

@section('content')
    <br>
    @if($data && $data->count() > 0)
        <table class="campaigns table-responsive-sm">
            <tr class="table table-striped table-sm">
                <th scope="col">ID</th>
                <th scope="col">SID</th>
                <th scope="col">Titel</th>
                <th scope="col" class="d-none d-sm-table-cell">Type</th>
                <th scope="col" class="d-none d-sm-table-cell">Erstellt am</th>
                <th scope="col" class="d-none d-sm-table-cell">Empfänger</th>
                <th scope="col" class="d-none d-sm-table-cell">gesendet</th>
                <th scope="col" class="d-none d-sm-table-cell">am</th>
                <th scope="col" class="d-none d-sm-table-cell">geöffnet</th>

                <th scope="col">Aktion</th>
            </tr>
            @foreach ($data as $item)
                    <?php
                    $sid = $item->getRecipients()->getSegmentOpts()->getSavedSegmentId();
                    ?>
                <tr data-cid="{{ $item->getId() }}" data-sid="{{ $sid }}">
                    <td>{{ $item->getId() }}</td>
                    <td>{{ $sid }}</td>
                    <td>{{ $item->getSettings()->getTitle() }}</td>
                    <td>{{ $item->getType() }} ({{ $item->getContentType() }})</td>
                    <td class="d-none d-sm-table-cell">{{ $item->getCreateTime()->format('d.m.Y H:i') }}</td>
                    <td class="members text-center d-none d-sm-table-cell"
                        data-title="Members"
                        data-html="true"
                        data-placement="top"
                        data-boundary="scrollParent"
                        data-trigger="click"
                        data-load="/admin/newsletter/members/{{ $sid }}"
                    >{{ $item->getRecipients()->getRecipientCount() }}</td>
                    <td class="text-center d-none d-sm-table-cell">{{ $item->getEmailsSent() }}</td>
                    <td class="text-center d-none d-sm-table-cell">@if($item->getSendTime() instanceof Carbon)
                            {{ $item->getSendTime()->format('d.m.Y H:i') }}
                        @else
                            <br>
                        @endif</td>
                    <td class="text-center d-none d-sm-table-cell">@if($item->getReportSummary())
                            {{ $item->getReportSummary()->getUniqueOpens() }}
                        @else
                            <br>
                        @endif</td>

                    <td><a class="btn btn-primary btn-sm text-center"
                           href="{{ url()->route("admin.newsletterEdit", ['campaignId' => $item->getId()]) }}">
                            <i class="ion-md-create mr-md-1 align-middle" aria-hidden="true"></i>
                            <span class="d-none d-sm-inline">Edit</span></a>
                    </td>
                    <td><a class="btn btn-danger btn-sm text-center softdel"
                           href="{{ url()->route("admin.newsletterDelete", ['campaignId' => $item->getId()]) }}">
                            <i class="ion-md-trash mr-md-1 align-middle" aria-hidden="true"></i>
                            <span class="d-none d-sm-inline">Löschen</span></a>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="alert">Keine Daten vorhanden</div>
    @endif

    <table id="popoverTable" style="display:none">
        <thead>
        <tr>
            <th>Email</th>
            <th title="Empfänger hat Mail geöffnet">opens</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection

@section('inline-scripts')
    <script>
		$('*[data-load]').click(function (evt) {
			var $this = $(this);
			$this.off(evt);
			$.get($this.data('load'), function (response) {
				$(response.result).each(function () {
					$("tbody", "#popoverTable").append("<tr><td>" + this.email_address + "</td><td>" + this.stats.avg_open_rate + "</td></tr>");
				});
				$this.popover({content: $("#popoverTable").show()}).popover('show');
			});
		});
    </script>
@endsection
