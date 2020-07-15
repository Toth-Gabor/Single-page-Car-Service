<div class="col-md-12">
    <div class="card">
        <div class="card-header"><strong>Ügyfelek</strong></div>
        <div id="load">
            <table id="client-table" class="table table-bordered table-striped">
                <thead class="thead-dark">
                <tr>
                    <th>Ügyfélazonosító</th>
                    <th>Név</th>
                    <th>Okmányazonosító</th>
                </tr>
                </thead>
                <tbody>
                <!--ügyfelek kilistázása-->
                @foreach ($clients as $client)
                    <tr>
                        <td>{{{ $client->id }}}</td>
                        <td class="client-name" data-id="{{{ $client->id }}}">{{{ $client->name }}}</td>
                        <td>{{{ $client->idcard }}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <!--pagination-->
            <div class="justify-content-center">
                {!! $clients->render() !!}
            </div>
        </div>
    </div>
</div>
