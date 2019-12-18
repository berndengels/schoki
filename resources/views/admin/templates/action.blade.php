
<td><a class="btn btn-primary btn-sm text-center" href="{{ url()->route("admin.${entity}Edit", ['id' => $item->id]) }}">
        <i class="ion-md-create mr-md-1 align-middle" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">ändern</span></a>
</td>

<td><a class="btn btn-danger btn-sm text-center softdel" href="{{ url()->route("admin.${entity}Delete", ['id' => $item->id]) }}">
        <i class="ion-md-trash mr-md-1 align-middle" aria-hidden="true"></i>
        <span class="d-none d-sm-inline">löschen</span></a>
</td>
