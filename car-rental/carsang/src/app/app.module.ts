import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { DataTablesModule } from 'angular-datatables';

import { AppComponent } from './app.component';
import { AddManufacturerComponent } from './add-manufacturer/add-manufacturer.component';
import { AppRoutingModule } from './app-routing.module';
import { AddModelsComponent } from './add-models/add-models.component';
import { ViewInventoryComponent } from './view-inventory/view-inventory.component';

@NgModule({
  declarations: [
    AppComponent,
    AddManufacturerComponent,
    AddModelsComponent,
    ViewInventoryComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
	DataTablesModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
