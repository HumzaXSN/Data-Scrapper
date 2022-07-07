<table>
    <thead>
        <tr>
            <th> Company </th>
            <th> Phone </th>
            <th> Address </th>
            <th> Website </th>
            <th> Industry </th>
            <th> Decision Maker </th>
            <th> Decision Maker Email</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($googleBusinessesData))
            @foreach ($googleBusinessesData as $key => $value)
                @php
                    $googleBusiness = App\Models\GoogleBusiness::where('id', $value)->first();
                @endphp
                @if ($googleBusiness->decisionMakers->count() <= 0)
                    <tr>
                        <td> {{ $googleBusiness->company }} </td>
                        <td> {{ $googleBusiness->phone }} </td>
                        <td> {{ $googleBusiness->address }} </td>
                        <td> {{ $googleBusiness->website }} </td>
                        <td> {{ $googleBusiness->industry }} </td>
                    </tr>
                @endif
                @foreach ($googleBusiness->decisionMakers as $decisionMaker)
                    @if ($decisionMaker->decisionMakerEmails->count() <= 0)
                        <tr>
                            <td> {{ $googleBusiness->company }} </td>
                            <td> {{ $googleBusiness->phone }} </td>
                            <td> {{ $googleBusiness->address }} </td>
                            <td> {{ $googleBusiness->website }} </td>
                            <td> {{ $googleBusiness->industry }} </td>
                            <td> {{ $decisionMaker->name }} </td>
                        </tr>
                    @endif
                    @foreach ($decisionMaker->decisionMakerEmails as $decisionMakerEmail)
                        <tr>
                            <td> {{ $googleBusiness->company }} </td>
                            <td> {{ $googleBusiness->phone }} </td>
                            <td> {{ $googleBusiness->address }} </td>
                            <td> {{ $googleBusiness->website }} </td>
                            <td> {{ $googleBusiness->industry }} </td>
                            <td> {{ $decisionMaker->name }} </td>
                            <td> {{ $decisionMakerEmail->email }} </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @else
            @foreach ($googleBusinesses as  $googleBusiness)
                @if ($googleBusiness->decisionMakers->count() <= 0)
                    <tr>
                        <td> {{ $googleBusiness->company }} </td>
                        <td> {{ $googleBusiness->phone }} </td>
                        <td> {{ $googleBusiness->address }} </td>
                        <td> {{ $googleBusiness->website }} </td>
                        <td> {{ $googleBusiness->industry }} </td>
                    </tr>
                @endif
                @foreach ($googleBusiness->decisionMakers as $decisionMaker)
                    @if ($decisionMaker->decisionMakerEmails->count() <= 0)
                    <tr>
                            <td> {{ $googleBusiness->company }} </td>
                            <td> {{ $googleBusiness->phone }} </td>
                            <td> {{ $googleBusiness->address }} </td>
                            <td> {{ $googleBusiness->website }} </td>
                            <td> {{ $googleBusiness->industry }} </td>
                            <td> {{ $decisionMaker->name }} </td>
                        </tr>
                    @endif
                    @foreach ($decisionMaker->decisionMakerEmails as $decisionMakerEmail)
                        <tr>
                            <td> {{ $googleBusiness->company }} </td>
                            <td> {{ $googleBusiness->phone }} </td>
                            <td> {{ $googleBusiness->address }} </td>
                            <td> {{ $googleBusiness->website }} </td>
                            <td> {{ $googleBusiness->industry }} </td>
                            <td> {{ $decisionMaker->name }} </td>
                            <td> {{ $decisionMakerEmail->email }} </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        @endif
    </tbody>
</table>
